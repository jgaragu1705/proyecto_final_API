<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AcademyController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Consultar academias sin necesidad de autenticación
Route::get('/academies', [AcademyController::class, 'index']);
Route::get('/academies/{academy}', [AcademyController::class, 'show']);

// Rutas que requieren autenticación
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/academies', [AcademyController::class, 'store']);
    Route::put('/academies/{academy}', [AcademyController::class, 'update']);
    Route::patch('/academies/{academy}', [AcademyController::class, 'update']);
    Route::delete('/academies/{academy}', [AcademyController::class, 'destroy']);
});
