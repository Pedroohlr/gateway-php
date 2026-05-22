<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Adquirentes\MercadopagoController;


Route::post('mercadopago/callback/deposit', [MercadopagoController::class, 'callbackDeposit']);
