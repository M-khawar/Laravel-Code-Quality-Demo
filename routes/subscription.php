<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubscriptionController;


Route::get('/create-payment-method', [SubscriptionController::class, 'createPaymentMethod']);

Route::post('/subscription/buy', [SubscriptionController::class, 'buySubscription'])
    ->middleware('auth:sanctum');
