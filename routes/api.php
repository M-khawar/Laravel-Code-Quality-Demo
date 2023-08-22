<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AdminCourseController,
    CalendarController,
    CourseController,
    LeadController,
    OnboardingController,
    PromoteController,
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

Route::get('/users', [UserController::class, 'getUsers'])->middleware('auth:sanctum');

Route::post('/visits', [LeadController::class, 'storeVisits']);

Route::get('/videos/{slug}', [VideoController::class, 'getVideoBySlug']);

/*** Leads Routes ***/
Route::group(['prefix' => 'leads'], function () {
    Route::post('/', [LeadController::class, 'store']);
    Route::delete('/{uuid}', [LeadController::class, 'destroyLead'])->middleware('auth:sanctum');
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


/*** Admin-Courses Sections Routes ***/
Route::group(['prefix' => 'admin-courses/sections', 'middleware' => 'auth:sanctum'], function () {
    Route::post('/edit', [AdminCourseController::class, 'editSection']);
    Route::post('/sort', [AdminCourseController::class, 'sortSection']);
    Route::post('/', [AdminCourseController::class, 'createSection']);
    Route::delete('/{uuid}', [AdminCourseController::class, 'destroySection']);
});

/*** Admin-Courses Lesson Routes ***/
Route::group(['prefix' => 'admin-courses/lessons', 'middleware' => 'auth:sanctum'], function () {
    Route::post('/edit', [AdminCourseController::class, 'editLesson']);
    Route::post('/sort', [AdminCourseController::class, 'sortLesson']);
    Route::post('/', [AdminCourseController::class, 'createLesson']);
    Route::delete('/{uuid}', [AdminCourseController::class, 'destroyLesson']);
});

/*** Admin-Courses Routes ***/
Route::group(['prefix' => 'admin-courses', 'middleware' => 'auth:sanctum'], function () {
    Route::post('/', [AdminCourseController::class, 'createCourse']);
    Route::post('/{uuid}/edit', [AdminCourseController::class, 'editCourse']);
    Route::post('/update-permissions', [AdminCourseController::class, 'updatePermissions']);
    Route::get('/roles', [AdminCourseController::class, 'coursesAudience']);
    Route::get('/all', [AdminCourseController::class, 'adminCourses']);
    Route::get('/{uuid}', [AdminCourseController::class, 'adminSingleCourse']);
    Route::delete('/{uuid}', [AdminCourseController::class, 'destroyCourse']);
});


require __DIR__ . '/auth.php';
require __DIR__ . '/subscription.php';
