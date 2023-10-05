<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    CalendarController,
    CourseController,
    LeadController,
    MediaController,
    OnboardingController,
    PromoteController,
    SupportController,
    UserController,
    VideoController
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

Route::get('/videos/live-call', [VideoController::class, 'getLiveCall']);
Route::get('/videos/{slug}', [VideoController::class, 'getVideoBySlug']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/users', [UserController::class, 'getUsers']);
    Route::post("/upload-media", [MediaController::class, 'uploadMedia']);
});

/*** Leads Routes ***/
Route::group(['prefix' => 'leads'], function () {
    Route::post('/', [LeadController::class, 'store']);
    Route::delete('/{uuid}', [LeadController::class, 'destroyLead'])->middleware('auth:sanctum');
    Route::get('/{uuid?}', [LeadController::class, 'getLead'])->middleware('auth:sanctum');
});

Route::get('/members/{uuid?}', [LeadController::class, 'getMembers'])->middleware('auth:sanctum');
Route::get('/leaderboard', [LeadController::class, 'leaderboard'])->middleware('auth:sanctum');

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

/*** Calendar Routes ***/
Route::group(['prefix' => 'calendar', 'middleware' => 'auth:sanctum'], function () {
    Route::post('/', [CalendarController::class, 'store']);
    Route::post('/{uuid}/edit', [CalendarController::class, 'edit']);
    Route::delete('/{uuid}', [CalendarController::class, 'destroy']);
    Route::get('/events-date', [CalendarController::class, 'eventsDate']);
    Route::get('/', [CalendarController::class, 'index']);
});

/*** Calendar-Notifications Routes ***/
Route::group(['prefix' => 'calendar-notifications', 'middleware' => 'auth:sanctum'], function () {
    Route::post('/', [CalendarController::class, 'storeNotification']);
    Route::post('/{uuid}/edit', [CalendarController::class, 'editNotification']);
    Route::delete('/{uuid}', [CalendarController::class, 'destroyNotification']);
});

/*** Courses Routes ***/
Route::group(['prefix' => 'courses', 'middleware' => 'auth:sanctum'], function () {
    Route::post('/mark-lesson-status', [CourseController::class, 'markLessonStatus']);
    Route::get('/categories/{uuid}', [CourseController::class, 'coursesByCategory']);
    Route::get('/categories', [CourseController::class, 'categories']);
    Route::get('/{uuid}/lessons', [CourseController::class, 'courseLessons']);
});

/*** Support Routes ***/
Route::group(['prefix' => 'support', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/issue-categories', [SupportController::class, 'issueCategories']);
    Route::post('/submit-ticket', [SupportController::class, 'submitTicket']);
});

/*** Notes Routes ***/
Route::group(['prefix' => 'notes', 'middleware' => 'auth:sanctum'], function () {
    Route::post('/edit', [OnboardingController::class, 'editNote']);
    Route::post('/', [OnboardingController::class, 'storeNote']);
    Route::delete('/{uuid}', [OnboardingController::class, 'destroyNote']);
    Route::get('/{uuid}', [OnboardingController::class, 'notes']);
});

require __DIR__ . '/auth.php';
require __DIR__ . '/subscription.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/chat.php';
