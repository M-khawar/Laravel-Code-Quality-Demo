<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubscriptionController;


Route::get('/create-payment-method', [SubscriptionController::class, 'createPaymentMethod']);

Route::get('/create-client-secret', [SubscriptionController::class, 'createClientSecret']);

Route::get('/subscription/plans', [SubscriptionController::class, 'getSubscriptionPlans']);

Route::post('/update-payment-card', [SubscriptionController::class, 'updatePaymentMethod'])
    ->middleware('auth:sanctum');

Route::post('/subscription/cancel', [SubscriptionController::class, 'cancelSubscription'])
    ->middleware('auth:sanctum');

Route::post('/subscription/resume', [SubscriptionController::class, 'resumeSubscription'])
    ->middleware('auth:sanctum');

Route::post('/subscription/change-plan', [SubscriptionController::class, 'changeSubscriptionPlan'])
    ->middleware('auth:sanctum');
