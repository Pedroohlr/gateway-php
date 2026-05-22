<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CallbackController;


Route::post('cartwave/callback/deposit', [CallbackController::class, 'callbackDeposit']);
Route::post('cartwave/callback/withdraw', [CallbackController::class, 'callbackWithdraw']);
