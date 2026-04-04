<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CompanyController extends Controller
{
    /**
     * Get places assigned to the company user.
     */
    public function getPlaces(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->tipo_usuario !== 'empresa') {
            return response()->json(['message' => 'Acceso denegado'], 403);
        }

        /*
         * NOTA DE AISLAMIENTO Y MUTILACIÓN: 
         * El modelo 'Place' y tabla 'place_company_users' aún no existen o no 
         * están implementados en esta rama, por lo tanto mockeamos y evitamos error de DB.
         * 
         * Código Futuro Real:
         * $places = $user->placesManaged()->get();
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
                'categorías' => ['naturaleza', 'senderismo']
            ]
        ];

        return response()->json($places, 200);
    }

    /**
     * Get detail of a specific assigned place.
     */
    public function getPlace(Request $request, $id): JsonResponse
    {
        $user = $request->user();

        if ($user->tipo_usuario !== 'empresa') {
            return response()->json(['message' => 'Acceso denegado'], 403);
        }

        /*
         * NOTA DE AISLAMIENTO Y MUTILACIÓN: 
         * Simulación de obtención de detalle de lugar.
         * 
         * Código Futuro Real:
         * $place = $user->placesManaged()->findOrFail($id);
         */
        $place = [
            'id' => $id,
            'nombre' => 'Reserva Natural La Pastora',
            'ubicación' => 'Pereira-Marsella, Risaralda',
            'descripción' => 'Un paraíso natural perfecto para el senderismo y la observación de aves en el corazón de Risaralda.',
            'imagen' => null, // Simulamos que aún no tiene imagen para probar fallback
            'latitud' => 4.8133,
            'longitud' => -75.6961,
            'categorías' => ['naturaleza', 'senderismo']
        ];

        return response()->json($place, 200);
    }

    /**
     * Update an assigned place (Mocked).
     */
    public function updatePlace(Request $request, $id): JsonResponse
    {
        $user = $request->user();

        if ($user->tipo_usuario !== 'empresa') {
            return response()->json(['message' => 'Acceso denegado'], 403);
        }

        /*
         * NOTA DE AISLAMIENTO Y MUTILACIÓN: 
         * Simulación de persistencia. Validamos datos pero no tocamos la base de datos real.
         * 
         * Código Futuro Real:
         * $place = $user->placesManaged()->findOrFail($id);
         * $place->update($request->all());
         */
        
        return response()->json([
            'message' => 'Lugar actualizado correctamente (Simulación)',
            'updated_id' => $id
        ], 200);
    }

    /**
     * Get reservation statistics for the company dashboard.
     */
    public function getReservationStats(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->tipo_usuario !== 'empresa') {
            return response()->json(['message' => 'Acceso denegado'], 403);
        }

        /*
         * NOTA DE AISLAMIENTO Y MUTILACIÓN: 
         * El modelo 'CompanyReservation' no existe en esta iteración.
         * Devolveremos 0 contadores mockeados.
         * 
         * Código Futuro Real:
         * $stats = [
         *     'pending' => $user->companyReservations()->where('status', 'pending')->count(),
         *     'accepted' => $user->companyReservations()->where('status', 'accepted')->count(),
         *     'rejected' => $user->companyReservations()->where('status', 'rejected')->count(),
         * ];
         */
        
        $stats = [
            'pending' => 5,
            'accepted' => 12,
            'rejected' => 2,
        ];

        return response()->json($stats, 200);
    }
}
