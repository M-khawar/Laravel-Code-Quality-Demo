<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    LeadController, OnboardingController, PromoteController, UserController, VideoController
};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/referral', [UserController::class, 'getReferral']);

Route::post('/visits', [LeadController::class, 'storeVisits']);

Route::get('/videos/{slug}', [VideoController::class, 'getVideoBySlug']);

/*** Leads Routes ***/
Route::group(['prefix' => 'leads'], function () {
    Route::post('/', [LeadController::class, 'store']);
    Route::get('/{uuid?}', [LeadController::class, 'getLead'])->middleware('auth:sanctum');
});

Route::get('/members/{uuid?}', [LeadController::class, 'getMembers'])->middleware('auth:sanctum');

/*** Onboarding Routes ***/
Route::group(['prefix' => 'onboarding', 'middleware' => 'auth:sanctum'], function () {
    Route::post('/answer', [OnboardingController::class, 'storeAnswer']);
    Route::post('/mark-step-status', [OnboardingController::class, 'markStepStatus']);
    Route::get('/questions', [OnboardingController::class, 'getQuestion']);
    Route::get('/progress', [OnboardingController::class, 'getProgress']);
});

/*** Promote Routes ***/
Route::group(['prefix' => 'promote', 'middleware' => 'auth:sanctum'], function () {
    Route::post('/settings', [PromoteController::class, 'settings']);
    Route::post('/stats', [PromoteController::class, 'getStats']);
});


require __DIR__ . '/auth.php';
require __DIR__ . '/subscription.php';
