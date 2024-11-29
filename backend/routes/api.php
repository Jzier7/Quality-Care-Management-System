<?php

use App\Http\Controllers\AbilityController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\UserController;
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

    //FOR USER REGSITRY, ADMIN AND USER ACCOUNTS PAGE
    Route::prefix('users')->group(function () {
        Route::post('store/admin', [UserController::class, 'store']);
        Route::delete('delete', [UserController::class, 'delete']);

        Route::prefix('update')->group(function () {
            Route::patch('data', [UserController::class, 'updateData']);
        });
    });
});

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('check', [AuthController::class, 'checkAuth']);
});
