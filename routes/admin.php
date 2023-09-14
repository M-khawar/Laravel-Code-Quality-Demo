<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AdminCourseController};

Route::post('/admin-stats', [AdminCourseController::class, 'adminStats'])->middleware('auth:sanctum');

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
