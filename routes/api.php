<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\ParticipationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::prefix('v1')->group(function () {
    // Auth routes
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', function (Request $request) {
            return $request->user();
        });

        // Events
        Route::apiResource('events', EventController::class);

        // Participations
        Route::post('events/{event}/join', [ParticipationController::class, 'join']);
        Route::post('events/{event}/leave', [ParticipationController::class, 'leave']);

        // User events
        Route::get('user/events', [EventController::class, 'userEvents']);
        Route::get('topEvents', [EventController::class, 'topEvents']);
        Route::get('user/participations', [ParticipationController::class, 'userParticipations']);
    });
});