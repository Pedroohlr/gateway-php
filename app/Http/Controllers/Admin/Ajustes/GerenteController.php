<?php

namespace App\Http\Controllers\Admin\Ajustes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\App;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class GerenteController extends Controller
{
    public function index()
    {
        $setting = App::first();
        $gerentes = User::where('permission', 9)->get();
        return view("admin.ajustes.gerentes", compact('setting', 'gerentes'));
    }
    
    public function create(Request $request)
    {
      $data = $request->except(['_token', '_method', '/administrador/ajustes/gerentes']);
        //dd($data);
         $senhaHash = Hash::make($data['password']);
         
        $id_unico = uniqid();
         
         $email = explode("@", $data['email']);
         
        $data['status'] = 1;
        $data['banido'] = 0;
        $data['username'] = $email[0];
        $data['cliente_id'] = $email[0];
        $data['user_id'] = $email[0];
        $data['password_temp'] = 1;
        $data['password'] = $senhaHash;
        
        User::create($data);

        return back()->with('success', 'Gerente cadastrado com sucesso!');
    }

    public function update($id, Request $request)
    {
        $data = $request->except(['_token', '_method', '/administrador/ajustes/gerentes']);
        $email = $request->input('email');
       // dd($data);
        $user = User::find($id);

        if (!isset($user)) {
            return redirect()->back()->with('error', "Gerente não encontrado!");
        }

        if (!is_null($email) && $user->email != $email) {
            $validation = $request->validate([
                'email' => ['unique:users,email'],
            ]);

            if (!$validation) {
                return redirect()->back()->with('error', "Email já cadastrado na base!");
            }
        }
        //dd($data);
        if($data['password']){
            $data['password'] = Hash::make($data['password']);
            $data['password_temp'] = 1;
            
        }
        if(isset($data['banido']) && $data['banido'] == "0"){
            $data['banido'] = 0;
        }
        
        if(isset($data['banido']) && $data['banido'] == "1"){
            $data['banido'] = 1;
        }
        
        
        foreach ($data as $key => $value) {
            if (is_null($value)) {
                unset($data[$key]);
            }
        }

       // dd($data);
        
         $user->update($data);

        return back()->with('success', 'Gerente alterado com sucesso!');
    }
    
    
}
