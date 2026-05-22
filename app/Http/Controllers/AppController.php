<?php

namespace App\Http\Controllers;

use App\Models\Fmcdevice;
use Illuminate\Http\Request;

class AppController extends Controller
{
    public function index(Request $request)
    {
        $devices = auth()->user()->devices;
        return view('profile.app', compact('devices'));
    }
}
