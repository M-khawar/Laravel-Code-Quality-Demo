<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubscriptionController;


Route::get('/create-payment-method', [SubscriptionController::class, 'createPaymentMethod']);

Route::post('/subscription/cancel', [SubscriptionController::class, 'cancelSubscription'])
    ->middleware('auth:sanctum');

Route::post('/subscription/resume', [SubscriptionController::class, 'resumeSubscription'])
    ->middleware('auth:sanctum');
