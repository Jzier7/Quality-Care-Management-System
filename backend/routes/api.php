<?php

use App\Http\Controllers\AbilityController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HealthCareWorkerController;
use App\Http\Controllers\PatientController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'role.guard'])->group(function () {

    Route::prefix('routes')->group(function () {
        Route::post('index', [RouteController::class, 'index']);
    });

    Route::prefix('user')->group(function () {
        Route::get('me', [UserController::class, 'me']);
    });

    Route::prefix('role')->group(function () {
        Route::post('index', [RoleController::class, 'index']);
    });

    Route::prefix('ability')->group(function () {
        Route::post('store', [AbilityController::class, 'store']);
        Route::patch('update', [AbilityController::class, 'update']);
    });

    Route::prefix('users')->group(function () {
        Route::post('store/admin', [UserController::class, 'store']);
        Route::delete('delete', [UserController::class, 'delete']);

        Route::prefix('update')->group(function () {
            Route::patch('data', [UserController::class, 'updateData']);
        });
    });

    Route::prefix('worker')->group(function () {
        Route::post('store', [HealthCareWorkerController::class, 'store']);
        Route::patch('update', [HealthCareWorkerController::class, 'update']);
        Route::delete('delete', [HealthCareWorkerController::class, 'delete']);

        Route::prefix('retrieve')->group(function () {
            Route::get('all', [HealthCareWorkerController::class, 'retrieveAll']);
            Route::get('paginated', [HealthCareWorkerController::class, 'retrievePaginate']);
        });
    });

    Route::prefix('patient')->group(function () {
        Route::post('store', [PatientController::class, 'store']);
        Route::patch('update', [PatientController::class, 'update']);
        Route::patch('update/status', [PatientController::class, 'updateStatus']);
        Route::delete('delete', [PatientController::class, 'delete']);

        Route::prefix('retrieve')->group(function () {
            Route::get('all', [PatientController::class, 'retrieveAll']);
            Route::get('paginated', [PatientController::class, 'retrievePaginate']);
        });
    });
});

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('check', [AuthController::class, 'checkAuth']);
});
