<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AcademyController;
use App\Http\Controllers\CourseController;

// Rutas de autenticación públicas
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Consultar cursos de una academia sin necesidad de autenticación
Route::get('/academy/{academy}/courses', [CourseController::class, 'coursesByAcademy']);

// Rutas que requieren autenticación
Route::middleware('auth:sanctum')->group(function () {

    // Cerrar sesión
    Route::post('/logout', [AuthController::class, 'logout']);

    // CRUD de academias
    Route::apiResource('academies', AcademyController::class);

    // CRUD de cursos
    Route::apiResource('courses', CourseController::class);

});
