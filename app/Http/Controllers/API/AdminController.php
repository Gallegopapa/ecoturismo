<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /**
     * Get all places for admin management.
     */
    public function index(Request $request): JsonResponse
    {
        if (!$request->user()->is_admin) {
            return response()->json(['message' => 'Acceso restringido a administradores'], 403);
        }

        /*
         * Código Futuro Real:
         * $places = \App\Models\Place::with('categories')->get();
         */
        $places = [
            [
                'id' => 1,
                'nombre' => 'Reserva Natural La Pastora',
                'ubicación' => 'Pereira-Marsella, Risaralda',
                'descripción' => 'Un paraíso natural perfecto para el senderismo y la observación de aves.',
                'imagen' => 'https://example.com/pastora.jpg',
                'latitud' => 4.8133,
                'longitud' => -75.6961,
                'is_active' => true,
                'categorías' => ['Naturaleza', 'Ecohotel']
            ],
            [
                'id' => 2,
                'nombre' => 'Parque Temático Flora y Fauna',
                'ubicación' => 'Pereira, Risaralda',
                'descripción' => 'Un espacio para conocer la biodiversidad del Eje Cafetero.',
                'imagen' => null,
                'latitud' => 4.8082,
                'longitud' => -75.7208,
                'is_active' => true,
                'categorías' => ['Familia', 'Parque']
            ]
        ];

        return response()->json($places, 200);
    }

    /**
     * Store a new ecotourism place.
     */
    public function store(Request $request): JsonResponse
    {
        if (!$request->user()->is_admin) {
            return response()->json(['message' => 'Acceso restringido'], 403);
        }

        // Simulación de validación
        $request->validate([
            'nombre' => 'required|string|max:255',
            'ubicación' => 'required|string',
            'descripción' => 'nullable|string',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        /*
         * Código Futuro Real:
         * $data = $request->except('imagen');
         * if ($request->hasFile('imagen')) {
         *     $data['imagen'] = $request->file('imagen')->store('places', 'public');
         * }
         * $place = \App\Models\Place::create($data);
         */

        return response()->json([
            'message' => 'Lugar ecoturístico creado con éxito (Simulación)',
            'id' => rand(100, 999)
        ], 201);
    }

    /**
     * Show detail for edit.
     */
    public function show(Request $request, $id): JsonResponse
    {
        if (!$request->user()->is_admin) {
            return response()->json(['message' => 'Acceso restringido'], 403);
        }

        /*
         * Código Futuro Real:
         * $place = \App\Models\Place::findOrFail($id);
         */
        $place = [
            'id' => $id,
            'nombre' => 'Reserva Natural La Pastora',
            'ubicación' => 'Pereira-Marsella, Risaralda',
            'descripción' => 'Un paraíso natural perfecto para el senderismo y la observación de aves.',
            'imagen' => null,
            'latitud' => 4.8133,
            'longitud' => -75.6961,
            'is_active' => true,
            'categorías' => ['Naturaleza']
        ];

        return response()->json($place, 200);
    }

    /**
     * Update an ecotourism place.
     */
    public function update(Request $request, $id): JsonResponse
    {
        if (!$request->user()->is_admin) {
            return response()->json(['message' => 'Acceso restringido'], 403);
        }

        /*
         * Código Futuro Real:
         * $place = \App\Models\Place::findOrFail($id);
         * $place->update($request->all());
         */

        return response()->json([
            'message' => 'Lugar ecoturístico actualizado con éxito (Simulación)'
        ], 200);
    }

    /**
     * Delete an ecotourism place.
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        if (!$request->user()->is_admin) {
            return response()->json(['message' => 'Acceso restringido'], 403);
        }

        /*
         * Código Futuro Real:
         * $place = \App\Models\Place::findOrFail($id);
         * $place->delete();
         */

        return response()->json([
            'message' => 'Lugar ecoturístico eliminado físicamente (Simulación)'
        ], 200);
    }
}
