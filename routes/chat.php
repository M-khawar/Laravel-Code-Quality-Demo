<?php


use App\Http\Controllers\{ChatController};
use Illuminate\Broadcasting\BroadcastController;
use Illuminate\Support\Facades\Route;

Route::post('broadcasting/auth', [BroadcastController::class, 'authenticate'])->middleware('auth:sanctum');

/*** Chat Routes ***/
Route::group(['prefix' => 'chat', 'middleware' => 'auth:sanctum'], function () {
    Route::post('/messages', [ChatController::class, 'sendMessage']);
    Route::get('/messages', [ChatController::class, 'fetchMessages']);
});
