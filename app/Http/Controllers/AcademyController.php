<?php

namespace App\Http\Controllers;

use App\Models\Academy;
use Illuminate\Http\Request;

class AcademyController extends Controller
{
    // Mostrar todas las academias
    public function index()
    {
        $academies = Academy::all();

        return response()->json($academies, 200);
    }

    // Crear una nueva academia
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        $academy = $request->user()->academies()->create($validated);

        return response()->json([
            'message' => 'Academia creada correctamente',
            'academy' => $academy,
        ], 201);
    }

    // Mostrar una academia concreta
    public function show(Academy $academy)
    {
        return response()->json($academy, 200);
    }

    // Actualizar una academia
    public function update(Request $request, Academy $academy)
    {
        // Comprobar que el usuario autenticado es el propietario
        if (!$academy->isOwnedBy($request->user())) {
            return response()->json([
                'message' => 'No tienes permiso para modificar esta academia',
            ], 403);
        }

        // Validaciones para PUT y PATCH
        if ($request->isMethod('put')) {
            $rules = [
                'name' => 'required|string|max:255',
                'description' => 'present|nullable|string',
                'address' => 'required|string|max:255',
                'phone' => 'required|string|max:255',
                'email' => 'required|email|max:255',
            ];
        } else {
            $rules = [
                'name' => 'sometimes|required|string|max:255',
                'description' => 'sometimes|nullable|string',
                'address' => 'sometimes|required|string|max:255',
                'phone' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|email|max:255',
            ];
        }

        $validated = $request->validate($rules);

        $academy->update($validated);

        return response()->json([
            'message' => 'Academia actualizada correctamente',
            'academy' => $academy,
        ], 200);
    }

    // Eliminar una academia
    public function destroy(Request $request, Academy $academy)
    {
        // Comprobar que el usuario autenticado es el propietario
        if (!$academy->isOwnedBy($request->user())) {
            return response()->json([
                'message' => 'No tienes permiso para eliminar esta academia',
            ], 403);
        }

        $academy->delete();

        return response()->noContent();
    }
}
