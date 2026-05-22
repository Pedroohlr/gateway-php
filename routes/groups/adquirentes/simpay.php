<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CallbackController;


Route::post('simpay/callback/deposit', [CallbackController::class, 'callbackDepositSimpay']);
Route::post('simpay/callback/withdraw', [CallbackController::class, 'callbackWithdrawSimpay']);
