<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CallbackController;


Route::post('apithekey/callback/deposit', [CallbackController::class, 'callbackDepositApithekey']);
Route::post('apithekey/callback/withdraw', [CallbackController::class, 'callbackWithdrawApithekey']);
