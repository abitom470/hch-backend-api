<?php

use App\Http\Controllers\Api\HealthMetricController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public Routes
Route::post('/login', [AuthController::class, 'apiLogin']);

// Protected Routes
Route::middleware('auth:sanctum')->prefix('health')->group(function () {
    
    // Recording Data
    Route::post('/metrics/record', [HealthMetricController::class, 'store']);
    Route::post('/symptoms/record', [HealthMetricController::class, 'storeSymptom']);
    
    // Dashboard & Analytics
    Route::get('/status', [HealthMetricController::class, 'getStatus']);
    Route::get('/history', [HealthMetricController::class, 'getHistory']);
    Route::get('/symptoms/history', [HealthMetricController::class, 'getSymptomHistory']);
    
    // THIS LINE MUST MATCH THE FUNCTION NAME IN HealthMetricController.php
    Route::get('/charts/{metricType}', [HealthMetricController::class, 'getChartData']);
});