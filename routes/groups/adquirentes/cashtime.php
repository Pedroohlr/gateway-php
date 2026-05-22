<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CallbackController;


Route::post('cashtime/callback/deposit', [CallbackController::class, 'callbackDeposit']);
Route::post('cashtime/callback/withdraw', [CallbackController::class, 'callbackWithdraw']);
