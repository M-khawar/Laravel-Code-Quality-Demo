<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\PermissionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware('guest')
    ->name('register');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest')
    ->name('login');

Route::post('/short-term-auth', [AuthenticatedSessionController::class, 'authByIdentity'])
    ->middleware('guest')
    ->name('short_term_auth');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.store');

Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
    ->middleware(['auth', 'signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth:sanctum')
    ->name('logout');

Route::post('/stepwise-validation', [RegisteredUserController::class, 'stepwiseValidation']);

Route::get('/me', [AuthenticatedSessionController::class, 'currentUserInfo'])
    ->middleware('auth:sanctum')
    ->name('current_user_info');

Route::get('/user/{uuid}', [AuthenticatedSessionController::class, 'userInfoByUuid'])
    ->middleware('auth:sanctum')
    ->name('user_info');

Route::get('/roles', [PermissionController::class, 'roles'])
    ->middleware('auth:sanctum')
    ->name('roles');

Route::post('/assign-role', [PermissionController::class, 'assignRole'])
    ->middleware('auth:sanctum')
    ->name('assign_roles');

Route::post('/update-administrator', [AuthenticatedSessionController::class, 'updateAdministrator'])
    ->middleware('auth:sanctum')
    ->name('update_administrator');
