<?php

namespace App\Http\Controllers;

use App\Models\Academy;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    // Mostrar todos los cursos
    public function index()
    {
        $courses = Course::all();

        return response()->json($courses, 200);
    }

    // Mostrar los cursos de una academia concreta (ruta pública)
    public function coursesByAcademy(Academy $academy)
    {
        $courses = $academy->courses;

        return response()->json($courses, 200);
    }

    // Mostrar un curso concreto
    public function show(Course $course)
    {
        return response()->json($course, 200);
    }

    // Crear un nuevo curso
    public function store(Request $request)
    {
        $validated = $request->validate([
            'academy_id' => 'required|integer|exists:academies,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'capacity' => 'required|integer|min:1',
        ]);

        // Obtener la academia donde se creará el curso
        $academy = Academy::findOrFail($validated['academy_id']);

        // Comprobar que el usuario autenticado es el propietario
        if (!$academy->isOwnedBy($request->user())) {
            return response()->json([
                'message' => 'No tienes permiso para crear cursos en esta academia',
            ], 403);
        }

        // Eliminar academy_id para crear el curso mediante la relación
        unset($validated['academy_id']);

        $course = $academy->courses()->create($validated);

        return response()->json([
            'message' => 'Curso creado correctamente',
            'course' => $course,
        ], 201);
    }

    // Actualizar un curso
    public function update(Request $request, Course $course)
    {
        // Comprobar que el usuario autenticado es el propietario de la academia
        if (!$course->academy->isOwnedBy($request->user())) {
            return response()->json([
                'message' => 'No tienes permiso para modificar este curso',
            ], 403);
        }

        // Validaciones para PUT y PATCH
        if ($request->isMethod('put')) {
            $rules = [
                'name' => 'required|string|max:255',
                'description' => 'present|nullable|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'capacity' => 'required|integer|min:1',
            ];
        } else {
            $rules = [
                'name' => 'sometimes|required|string|max:255',
                'description' => 'sometimes|nullable|string',
                'start_date' => 'sometimes|required|date',
                'end_date' => 'sometimes|required|date',
                'capacity' => 'sometimes|required|integer|min:1',
            ];
        }

        $validated = $request->validate($rules);

        // Comprobar que la fecha de finalización no sea anterior a la de inicio
        $startDate = $validated['start_date'] ?? $course->start_date;
        $endDate = $validated['end_date'] ?? $course->end_date;

        if (strtotime($endDate) < strtotime($startDate)) {
            return response()->json([
                'message' => 'La fecha de finalización no puede ser anterior a la fecha de inicio',
                'errors' => [
                    'end_date' => [
                        'La fecha de finalización debe ser igual o posterior a la fecha de inicio',
                    ],
                ],
            ], 422);
        }

        // Comprobar que la capacidad no sea inferior a los alumnos matriculados
        if (
            isset($validated['capacity']) &&
            $validated['capacity'] < $course->students()->count()
        ) {
            return response()->json([
                'message' => 'La capacidad no puede ser inferior al número de alumnos matriculados',
                'errors' => [
                    'capacity' => [
                        'La capacidad indicada es inferior al número de alumnos matriculados',
                    ],
                ],
            ], 422);
        }

        $course->update($validated);

        return response()->json([
            'message' => 'Curso actualizado correctamente',
            'course' => $course,
        ], 200);
    }

    // Eliminar un curso
    public function destroy(Request $request, Course $course)
    {
        // Comprobar que el usuario autenticado es el propietario de la academia
        if (!$course->academy->isOwnedBy($request->user())) {
            return response()->json([
                'message' => 'No tienes permiso para eliminar este curso',
            ], 403);
        }

        $course->delete();

        return response()->noContent();
    }
}
