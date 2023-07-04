<?php

use App\Http\Controllers\LeadController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/leads', [LeadController::class, 'store']);

Route::get('/videos/{slug}', [VideoController::class, 'getVideoBySlug']);

/*** Onboarding Routes ***/
Route::group(['prefix' => 'onboarding', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/questions', [OnboardingController::class, 'getQuestion']);
    Route::post('/answer', [OnboardingController::class, 'storeAnswer']);
    Route::post('/mark-step-status', [OnboardingController::class, 'markStepStatus']);
});


require __DIR__ . '/auth.php';
require __DIR__ . '/subscription.php';
