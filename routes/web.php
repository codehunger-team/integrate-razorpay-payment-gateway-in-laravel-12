<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

Route::get('/pay', [PaymentController::class, 'createOrder'])->name('payment.form');
Route::post('/verify-payment', [PaymentController::class, 'verifySignature'])->name('payment.verify');