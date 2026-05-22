<?php

use App\Http\Controllers\Api\Adquirentes\ZoompagController;
use Illuminate\Support\Facades\Route;

Route::post('zoompag/callback/deposit', [ZoompagController::class, 'callbackDeposit']);
Route::post('zoompag/callback/withdraw', [ZoompagController::class, 'callbackWithdraw']);
Route::post('zoompag/register/webhook', [ZoompagController::class, 'registerWebhook'])->name('zoompag.webhooks');