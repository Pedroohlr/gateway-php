<?php

namespace App\Http\Controllers\Admin\Ajustes;

use App\Http\Controllers\Controller;
use App\Models\Blupay;
use App\Models\Mercadopago;
use Illuminate\Http\Request;
use App\Models\{Adquirente, AdApiTheKey, AdSimpay, AdWitetec, AdZoompag, Cashtime, Cartwave};
use App\Models\App;
use Illuminate\Support\Facades\DB;

class AdquirentesController extends Controller
{
    public function index()
    {
        $cashtime = Cashtime::first();
        $cartwave = Cartwave::first();
        $apithekey = AdApiTheKey::first();
        $simpay = AdSimpay::first();
        $witetec = AdWitetec::first();
        $zoompag = AdZoompag::first();
        $mercadopago = Mercadopago::first();
        $blupay = Blupay::first();
        
        if(!$blupay){
            Blupay::create([]);
            $blupay = Blupay::first();
        }

        if(!$zoompag){
            AdZoompag::create([]);
            $zoompag = AdZoompag::first();
        }

        $settings = App::first();
        $adquirente = Adquirente::where('status', 1)->value('adquirente') ?? 'primepag';

        return view("admin.ajustes.adquirentes", compact(
            'adquirente',
            'cashtime',
            'apithekey',
            'simpay',
            'witetec',
            'settings',
            'cartwave',
            'zoompag',
            'mercadopago',
            'blupay'
        ));
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token', '_method']);
        $payload = [];
        foreach ($data as $key => $value) {
            if($key == 'secret'){
                $payload[$key] = $value;
            } else {
                $payload[$key] = (float) $value;
            }
        }
        //dd($request->all());
        $setting = Cashtime::first()->update($payload);

        return back()->with('success', 'Dados alterados com sucesso!');

        // Retornar uma resposta de sucesso
        return response('success');
    }
    
     public function updateCartwave(Request $request)
    {
        $data = $request->except(['_token', '_method']);
        $payload = [];
        foreach ($data as $key => $value) {
            if($key == 'secret'){
                $payload[$key] = $value;
            } else {
                $payload[$key] = (float) $value;
            }
        }
        //dd($request->all());
        $setting = Cartwave::first()->update($payload);

        return back()->with('success', 'Dados alterados com sucesso!');

        // Retornar uma resposta de sucesso
        return response('success');
    }
    
    public function updateApithekey(Request $request)
    {
        $data = $request->except(['_token', '_method']);
        $payload = [];
        foreach ($data as $key => $value) {
            if($key == 'client_secret' || $key == 'client_id'){
                $payload[$key] = $value;
            } else {
                $payload[$key] = (float) $value;
            }
        }
        //dd($request->all());
        $setting = AdApiTheKey::first()->update($payload);

        return back()->with('success', 'Dados alterados com sucesso!');

    }
    
     public function updateSimpay(Request $request)
    {
        $data = $request->except(['_token', '_method']);
        $data['x_api_key'] = $data['x_api_key'];
        $data['taxa_pix_cash_in'] = str_replace(",",".", $data['taxa_pix_cash_in']);
        $data['taxa_pix_cash_out'] = str_replace(",",".", $data['taxa_pix_cash_out']);
        
        //dd($request->all());
        $setting = AdSimpay::first()->update($data);

        return back()->with('success', 'Dados alterados com sucesso!');

    }
    
    public function updateWitetec(Request $request)
    {
        $data = [];

        $data['api_token'] = $request->input('api_token');
        $data['taxa_pix_cash_in'] = str_replace(",",".", $request->taxa_pix_cash_in);
        $data['taxa_pix_cash_out'] = str_replace(",",".", $request->taxa_pix_cash_out);
        
        AdWitetec::first()->update($data);

        return back()->with('success', 'Dados alterados com sucesso!');

    }

    public function updateZoompag(Request $request)
    {
        $data = [];

        $data['api_token'] = $request->input('api_token');
        $data['taxa_pix_cash_in'] = str_replace(",",".", $request->taxa_pix_cash_in);
        $data['taxa_pix_cash_out'] = str_replace(",",".", $request->taxa_pix_cash_out);
        
        AdZoompag::first()->update($data);

        return back()->with('success', 'Dados alterados com sucesso!');

    }
    public function updateBlupay(Request $request)
    {
        $data = [];

        $data['username'] = $request->input('username');
        $data['password'] = $request->input('password');
        $data['taxa_pix_cash_in'] = str_replace(",",".", $request->taxa_pix_cash_in);
        $data['taxa_pix_cash_out'] = str_replace(",",".", $request->taxa_pix_cash_out);
        
        Blupay::first()->update($data);

        return back()->with('success', 'Dados alterados com sucesso!');

    }

    public function updateMercadopago(Request $request)
    {
        Mercadopago::first()->update([
            'access_token' => $request->input('access_token'),
            'taxa_pix_cash_in' => str_replace(",",".", $request->input('taxa_pix_cash_in'))
        ]);

        return back()->with('success', 'Dados alterados com sucesso!');

    }
    
    
    public function updateDefault(Request $request)
    {
        DB::transaction(function () use ($request) {
            // 1. Zera o status de todos
            Adquirente::query()->update(['status' => 0]);
    
            // 2. Ativa o adquirente selecionado
            Adquirente::where('adquirente', $request->adquirente)
                      ->update(['status' => 1]);
        });
    
        return back()->with('success', 'Dados alterados com sucesso!');
    }
}
