<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DocumentacaoControlller extends Controller
{
    public function index()
    {
        return view("documentacao");
    }
}