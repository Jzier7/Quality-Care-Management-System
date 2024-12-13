<?php

use App\Http\Controllers\AbilityController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HealthCareWorkerController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\InformationBoardController;
use App\Http\Controllers\EmergencyContactController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PositionController;
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
            Route::patch('worker', [UserController::class, 'updateWorker']);
            Route::patch('patient', [UserController::class, 'updatePatient']);
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

    Route::prefix('informationBoard')->group(function () {
        Route::post('store', [InformationBoardController::class, 'store']);
        Route::delete('delete', [InformationBoardController::class, 'delete']);

        Route::prefix('retrieve')->group(function () {
            Route::get('all', [InformationBoardController::class, 'retrieveAll']);
            Route::get('paginated', [InformationBoardController::class, 'retrievePaginate']);
        });
    });

    Route::prefix('emergencyContact')->group(function () {
        Route::post('store', [EmergencyContactController::class, 'store']);
        Route::delete('delete', [EmergencyContactController::class, 'delete']);

        Route::prefix('retrieve')->group(function () {
            Route::get('all', [EmergencyContactController::class, 'retrieveAll']);
            Route::get('paginated', [EmergencyContactController::class, 'retrievePaginate']);
        });
    });

    Route::prefix('medicalRecord')->group(function () {
        Route::post('store', [MedicalRecordController::class, 'store']);
        Route::delete('delete', [MedicalRecordController::class, 'delete']);

        Route::prefix('retrieve')->group(function () {
            Route::get('', [MedicalRecordController::class, 'retrieve']);
            Route::get('all', [MedicalRecordController::class, 'retrieveAll']);
            Route::get('paginated', [MedicalRecordController::class, 'retrievePaginate']);
        });
    });

    Route::prefix('schedule')->group(function () {
        Route::post('store', [ScheduleController::class, 'store']);
        Route::patch('update', [ScheduleController::class, 'update']);
        Route::delete('delete', [ScheduleController::class, 'delete']);

        Route::prefix('retrieve')->group(function () {
            Route::get('patient', [ScheduleController::class, 'retrievePatient']);
            Route::get('worker', [ScheduleController::class, 'retrieveWorker']);
        });
    });

    Route::get('dashboard', [DashboardController::class, 'retrieve']);

    Route::prefix('position')->group(function () {
        Route::post('store', [PositionController::class, 'store']);
        Route::patch('update', [PositionController::class, 'update']);
        Route::delete('delete', [PositionController::class, 'delete']);

        Route::prefix('retrieve')->group(function () {
            Route::get('all', [PositionController::class, 'retrieveAll']);
            Route::get('paginated', [PositionController::class, 'retrievePaginate']);
        });
    });
});

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('update-password', [AuthController::class, 'updatePassword']);
});
