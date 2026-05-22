<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gamefication;

class GameficationController extends Controller
{
    public function index(Request $request)
    {
        $niveis = Gamefication::get();
        return view('admin.gamefication', compact('niveis'));   
    }

     public function add(Request $request)
    {
        $data = $request->only(['name', 'desc', 'min', 'max']);

        $imageFields = ['image'];

        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                // Faz o upload da nova imagem com um nome único
                $image = $request->file($field);
                $imageName = uniqid(). '.' . $image->getClientOriginalExtension();
                $destination = public_path('niveis');
                if (!file_exists($destination)) {
                    mkdir($destination, 0775, true);
                }
                $imagePath = $image->move($destination, $imageName);

                $data[$field] = '/niveis/'.$imageName;

            }
        }

        Gamefication::create($data);

        return redirect()->back()->with('success', 'Nível cadastrado com sucesso!');
    }

    public function edit(Request $request, $id)
    {
        $data = $request->only(['name', 'desc', 'min', 'max']);

        $imageFields = ['image'];

        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                // Faz o upload da nova imagem com um nome único
                $image = $request->file($field);
                $imageName = uniqid(). '.' . $image->getClientOriginalExtension();
                $destination = public_path('niveis');
                if (!file_exists($destination)) {
                    mkdir($destination, 0775, true);
                }
                $imagePath = $image->move($destination, $imageName);

                $data[$field] = '/niveis/'.$imageName;

            }
        }

        Gamefication::where('id', $id)->update($data);

        return redirect()->back()->with('success', 'Nível alterado com sucesso!');
    }

    public function excluir(Request $request, $id)
    {
        Gamefication::where('id', $id)->delete();

        return redirect()->back()->with('success', 'Nível excluído com sucesso!');  
    }

}
