<?php

use App\Http\Controllers\Api\Adquirentes\BlupayController;
use Illuminate\Support\Facades\Route;


Route::post('blupay/callback/deposit', [BlupayController::class, 'callbackDeposit']);
Route::post('blupay/callback/withdraw', [BlupayController::class, 'callbackWithdraw']);
