<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CheckoutBuild;
use App\Models\CheckoutDepoimento;
use App\Models\CheckoutOrders;
use App\Models\{Solicitacoes, SolicitacoesCashOut};
use App\Models\UsersKey;
use App\Models\{Adquirente, User};
use App\Traits\ApiTrait;
use App\Traits\{ApithekeyTrait, CashtimeTrait, SimpayTrait, WitetecTrait};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class CheckoutControlller extends Controller
{
    public function index()
    {
        $checkouts = CheckoutBuild::where("user_id", auth()->id())->get();

        return view("profile.checkout.index", compact("checkouts"));
    }

    public function indexEdit($id, Request $request)
    {
        $checkout = CheckoutBuild::where('id_unico', $id)->first();
        return view("profile.checkout.edit", compact('checkout'));
    }

    public function v1($id, Request $request)
    {
        $checkout = CheckoutBuild::where("id_unico", $id)->first();
        $user = User::where('id', $checkout->user_id)->first();
        $keys = UsersKey::where('user_id', $user->user_id)->first();
        $token = $keys->token;
        $secret = $keys->secret;

        return view('profile.checkout.v1', compact('checkout', 'secret', 'token'));
    }

    public function v2(Request $request)
    {
        $id = $request->input("id");
        $produto = CheckoutBuild::where("referencia", $id)->first();
        $keys = UsersKey::where('user_id', $produto->user_id)->first();
        $token = $keys->token;
        $secret = $keys->secret;

        return view('profile.checkout.v2', compact('produto', 'secret', 'token'));
    }

    public function create(Request $request)
    {

        $validated = $request->validate([
            "produto_name" => "required|string",
            "produto_valor" => "required|string",
            "produto_descricao" => "required|string",
            "produto_tipo" => "required|string",
            "produto_tipo_cob" => "required|string"
        ]);

        $data = $request->except(['_token', '_method', '/checkout']);

        $data['user_id'] = auth()->id();
        $data['id_unico'] = Str::uuid();
        $data['produto_valor'] = (float) str_replace(',','.', $data['produto_valor']);

        CheckoutBuild::create($data);
        return redirect()->back()->with('success', 'Checkout cadastrado com sucesso com sucesso!');
    }

    public function edit($id, Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'produto_image'        => 'nullable|image|max:2048', 
            'checkout_header_logo' => 'nullable|image|max:2048', 
            'checkout_header_image'=> 'nullable|image|max:2048', 
            'checkout_banner'      => 'nullable|image|max:2048',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
            
            
        // Criamos o registro sem as imagens
        $checkoutBuild = CheckoutBuild::where('id', $id)->first();
        $checkoutDir = public_path("/checkouts/{$checkoutBuild->id}/");
        if (!file_exists($checkoutDir)) {
            mkdir($checkoutDir, 0755, true);
        }
        $data = collect($request->all())
            ->reject(function ($value, $key) {
                return preg_match('/^checkout_depoimentos_/', $key)
                    || in_array($key, ['_token', '_method', 'checkout_depoimentos_id', 'checkout_depoimentos_nome', 'checkout_depoimentos_depoimento', 'checkout_depoimentos_image']);
            })
            ->toArray();

        $data['produto_valor'] = (float) str_replace(',','.', $data['produto_valor']);

        // Atualiza campos principais
        $checkoutBuild->update($data);

        // Atualiza imagens únicas como produto/banner/logo/etc
        $images_checkout = ['produto_image', 'checkout_header_logo', 'checkout_header_image', 'checkout_banner'];
        $dataImg = [];

        foreach ($images_checkout as $field) {
            if ($request->hasFile($field)) {
                $filename = 'checkout_' . $field . '.' . $request->file($field)->getClientOriginalExtension();
                $request->file($field)->move($checkoutDir, $filename);
                $dataImg[$field] = "/checkouts/{$checkoutBuild->id}/{$filename}";
            }
        }

        // Atualiza imagens únicas, se houver
        if (!empty($dataImg)) {
            $checkoutBuild->update($dataImg);
        }


        $checkoutBuild->fill([
            'checkout_timer_active' => $request->has('checkout_timer_active'),
            'checkout_header_logo_active' => $request->has('checkout_header_logo_active'),
            'checkout_header_image_active' => $request->has('checkout_header_image_active'),
            'checkout_topbar_active' => $request->has('checkout_topbar_active'),
            'checkout_banner_active' => $request->has('checkout_banner_active'),
            // outros campos...
        ])->save();

        return redirect()->back()->with('success', 'Checkout alterado com sucesso!');
    }

    public function destroy($id)
    {
        // Buscar o checkout pelo ID
        $checkout = CheckoutBuild::find($id);

        if (!$checkout) {
            return redirect()->back()->with('error', 'Checkout não encontrado.');
        }

        // Deleta as imagens associadas, se existirem
        if ($checkout->logo_produto) {
            Storage::disk('public')->delete($checkout->logo_produto);
        }
        if ($checkout->banner_produto) {
            Storage::disk('public')->delete($checkout->banner_produto);
        }

        // Exclui o checkout do banco de dados
        $checkout->delete();

        return redirect()->back()->with('success', 'Checkout excluído com sucesso!');
    }

    public function gerarPedido(Request $request)
    {
        $data = $request->except(['_token']);
        $venda = CheckoutOrders::create($data);

        if (!$venda) {
            return response()->json(['status' => 'error', 'message' => 'Houve um erro. Tente novamente!']);
        }

        $checkout = CheckoutBuild::where('id', $venda->checkout_id)->first();
        $user = User::where('id', $checkout->user_id)->first();
        $chaves = UsersKey::where('user_id', $user->user_id)->first();

        $dataRequest = [
            'amount' => $venda->valor_total,
            'debtor_name' => $venda->name,
            'email' => $venda->email,
            'debtor_document_number' => $venda->cpf,
            'phone' => $venda->telefone,
            'method_pay' => 'pix',
            'postback' => 'web',
            'user' => $user
        ];

        $request = new Request($dataRequest);


        $adquirente = Adquirente::where('status', 1)->first()['adquirente'];
        
        switch($adquirente){
            case 'cashtime':
                $response = CashtimeTrait::requestDepositCashtime($request);
                break;
            case 'apithekey':
                $response = ApithekeyTrait::requestDepositApithekey($request);
                break;
            case 'simpay':
                $response = SimpayTrait::requestDepositSimpay($request);
                break;
            case 'witetec':
                $response = WitetecTrait::requestDepositWitetec($request);
                break;
        }
        //dd($response);
        $status = isset($response['status']) && $response['status'] == 200 ? 'success' : 'error';
        if ($status == "success") {
            $cashin = Solicitacoes::where('idTransaction', $response['data']['idTransaction'])->first();
            $cashin->update(['descricao_transacao' => 'PRODUTO']);
            

            $venda->idTransaction = $response['data']['idTransaction'];
            $venda->qrcode = $response['data']['qrcode'];
            $venda->save();
            $valor_text = "R$ " . number_format($venda->valor_total, '2', ',', '.');
            return response()->json(["status" => $status, "data" => $response['data'], "valor_text" => $valor_text]);
        } else {
            return response()->json(['status' => 'error', 'message' => "Verifique e tente novamente."]);
        }
    }

    public function statusPedido(Request $request)
    {
        $data = $request->except(['/checkout/cliente/pedido/status']);
        //dd($data);
        $order = CheckoutOrders::where('idTransaction', $data['idTransaction'])->first();

        $status = $order->status;
        $message = "Aguardando pagamento...";
        if ($status == 'pago') {
            $message = "Pagamento realizado com sucesso!";
        }
        return response()->json(compact('status', 'message'));
        //dd($data, $order);
    }

    public function salvarDepoimento(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'depoimento' => 'required|string|max:1000',
            'image' => 'nullable|image|max:2048',
            'avatar' => 'nullable|string',
            'id' => 'nullable|string',
            'checkout_id' => 'required'
        ]);

        $depoimento = [
            'id' => $validated['id'],
            'nome' => $validated['nome'],
            'depoimento' => $validated['depoimento'],
            'avatar' => $validated['avatar'] ?? null,
            'checkout_id' => $validated['checkout_id'],
        ];

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = 'dep_' . $depoimento['id'] .'_'.uniqid(). '.' . $file->getClientOriginalExtension();
            $path = "checkouts/{$depoimento['id']}/";
            $file->move(public_path($path), $filename);
            $depoimento['avatar'] = '/' . $path . $filename;
        }
        //dd($depoimento);
        // Aqui você pode salvar em banco se quiser
        if (is_null($depoimento['id'])) {
            unset($depoimento['id']);
            $depoimento = DB::table('checkout_depoimentos')->insert($depoimento);
        } else {
           // dd($depoimento);
            DB::table('checkout_depoimentos')->where('id', $depoimento['id'])->update($depoimento);
        }


        return response()->json([
            'success' => true,
            'depoimento' => $depoimento
        ]);
    }


    public function removerDepoimento(Request $request)
    {
        $id = $request->input('id');

        if (!$id) {
            return response()->json(['success' => false, 'message' => 'ID não informado.'], 400);
        }

        $depoimento = CheckoutDepoimento::find($id);

        if (!$depoimento) {
            return response()->json(['success' => false, 'message' => 'Depoimento não encontrado.'], 404);
        }

        try {
            $depoimento->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erro ao remover depoimento.']);
        }
    }
}
