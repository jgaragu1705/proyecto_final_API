<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EnrollmentController extends Controller
{
    // Mostrar los estudiantes matriculados en un curso
    public function index(Course $course)
    {
        $students = $course->students()->get();

        return response()->json($students, 200);
    }

    // Matricular un estudiante en un curso
    public function enroll(Request $request, Course $course, Student $student)
    {
        // Comprobar que el usuario es propietario de la academia
        if (!$course->academy->isOwnedBy($request->user())) {
            return response()->json([
                'message' => 'No tienes permiso para matricular estudiantes en este curso',
            ], 403);
        }

        // Realizar las comprobaciones y la matrícula dentro de una transacción
        return DB::transaction(function () use ($course, $student) {

            // Bloquear el curso durante la operación para evitar superar su capacidad
            $course = Course::whereKey($course->id)
                ->lockForUpdate()
                ->firstOrFail();

            // Comprobar si el estudiante ya está matriculado
            if ($course->students()->where('students.id', $student->id)->exists()) {
                return response()->json([
                    'message' => 'El estudiante ya está matriculado en este curso',
                ], 409);
            }

            // Comprobar si el curso ha alcanzado su capacidad máxima
            if ($course->students()->count() >= $course->capacity) {
                return response()->json([
                    'message' => 'El curso ha alcanzado su capacidad máxima',
                ], 409);
            }

            // Crear la matrícula y guardar la fecha
            $course->students()->attach($student->id, [
                'enrolled_at' => today()->toDateString(),
            ]);

            // Obtener el estudiante con los datos de la matrícula
            $enrolledStudent = $course->students()
                ->where('students.id', $student->id)
                ->first();

            return response()->json([
                'message' => 'Estudiante matriculado correctamente',
                'student' => $enrolledStudent,
            ], 201);
        });
    }

    // Desmatricular un estudiante de un curso
    public function unenroll(Request $request, Course $course, Student $student)
    {
        // Comprobar que el usuario es propietario de la academia
        if (!$course->academy->isOwnedBy($request->user())) {
            return response()->json([
                'message' => 'No tienes permiso para desmatricular estudiantes de este curso',
            ], 403);
        }

        // Eliminar la matrícula
        $detached = $course->students()->detach($student->id);

        // Comprobar si existía la matrícula
        if ($detached === 0) {
            return response()->json([
                'message' => 'El estudiante no está matriculado en este curso',
            ], 404);
        }

        return response()->json([
            'message' => 'Estudiante desmatriculado correctamente',
        ], 200);
    }
}
