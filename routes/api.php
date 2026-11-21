<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AcademyController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;

// Rutas de autenticación públicas
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Consultar cursos de una academia sin autenticación
Route::get('/academy/{academy}/courses', [CourseController::class, 'coursesByAcademy']);

// Rutas que requieren autenticación
Route::middleware('auth:sanctum')->group(function () {

    // Cerrar sesión
    Route::post('/logout', [AuthController::class, 'logout']);

    // CRUD de academias
    Route::apiResource('academies', AcademyController::class);

    // CRUD de cursos
    Route::apiResource('courses', CourseController::class);

    // Gestión de matrículas
    Route::get('/courses/{course}/students', [EnrollmentController::class, 'index']);
    Route::post('/courses/{course}/enroll/{student}', [EnrollmentController::class, 'enroll']);
    Route::delete('/courses/{course}/unenroll/{student}', [EnrollmentController::class, 'unenroll']);
});
