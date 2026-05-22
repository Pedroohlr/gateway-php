<?php

use App\Http\Controllers\PushController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SaqueController;
use App\Http\Controllers\Api\DepositController;
use Illuminate\Support\Facades\Artisan;

Route::get('link-storage', function () {
    Artisan::call('storage:unlink');
    Artisan::call('storage:link');
    return redirect('/');
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('check.token.secret')->post('transaction/deposit', [DepositController::class, 'makeDeposit']);
Route::middleware([/*'throttle:custom-ip-limit',*/ 'check.token.secret'])->post('transaction/payment', [SaqueController::class, 'makePayment']);
Route::post('status', [DepositController::class, 'statusDeposito']);
