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
        $places = [];

        return response()->json($places, 200);
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
            'pending' => 0,
            'accepted' => 0,
            'rejected' => 0,
        ];

        return response()->json($stats, 200);
    }
}
