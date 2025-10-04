<?php

use App\Http\Controllers\ApiOrderController;
use App\Http\Controllers\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('v2', [ApiOrderController::class, 'process'])->middleware(['throttle:api'])->name('api.v2');

// Webhook url
Route::post('korapay/webhook', [PaymentController::class, 'korapayWebhook'])->name('korapay.webhook');
