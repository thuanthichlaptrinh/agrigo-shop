<?php

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\ChatbotController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group.
|
*/

Route::prefix('v1')->name('api.v1.')->group(function () {

    // Auth routes với session support (cho web login)
    Route::prefix('auth')->name('auth.')->middleware(['web'])->group(function () {
        Route::post('/register', [AuthApiController::class, 'register'])->name('register');
        Route::post('/login', [AuthApiController::class, 'login'])->name('login');
        Route::post('/forgot-password', [AuthApiController::class, 'forgotPassword'])->name('forgot-password');
        Route::post('/reset-password', [AuthApiController::class, 'resetPassword'])->name('reset-password');
        Route::post('/logout', [AuthApiController::class, 'logout'])->name('logout');
    });

    // Chatbot API (no CSRF)
    Route::post('/chatbot/query', [ChatbotController::class, 'query'])->name('chatbot.query');

    // Protected routes (cần xác thực JWT)
    Route::middleware('jwt.auth')->group(function () {
        Route::prefix('auth')->name('auth.')->group(function () {
            Route::post('/refresh', [AuthApiController::class, 'refresh'])->name('refresh');
            Route::get('/me', [AuthApiController::class, 'me'])->name('me');
            Route::put('/profile', [AuthApiController::class, 'updateProfile'])->name('update-profile');
            Route::put('/change-password', [AuthApiController::class, 'changePassword'])->name('change-password');
        });
    });
});
