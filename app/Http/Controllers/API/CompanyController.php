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

        $place = [
            'id' => $id,
            'nombre' => 'Reserva Natural La Pastora',
            'ubicación' => 'Pereira-Marsella, Risaralda',
            'descripción' => 'Un paraíso natural perfecto para el senderismo y la observación de aves en el corazón de Risaralda.',
            'imagen' => null,
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

        return response()->json([
            'message' => 'Lugar actualizado correctamente (Simulación)',
            'updated_id' => $id
        ], 200);
    }

    /**
     * Get schedules for a specific place (Mocked).
     */
    public function getSchedules(Request $request, $id): JsonResponse
    {
        $user = $request->user();

        if ($user->tipo_usuario !== 'empresa') {
            return response()->json(['message' => 'Acceso denegado'], 403);
        }

        /*
         * Código Futuro Real:
         * $place = $user->placesManaged()->findOrFail($id);
         * $schedules = $place->schedules()->orderBy('id')->get();
         */
        $schedules = [
            [
                'id' => 101,
                'place_id' => $id,
                'dia_semana' => 'lunes',
                'hora_inicio' => '08:00',
                'hora_fin' => '17:00',
                'activo' => true
            ],
            [
                'id' => 102,
                'place_id' => $id,
                'dia_semana' => 'martes',
                'hora_inicio' => '08:00',
                'hora_fin' => '17:00',
                'activo' => true
            ]
        ];

        return response()->json($schedules, 200);
    }

    /**
     * Store a new schedule for a specific place (Mocked).
     */
    public function storeSchedule(Request $request, $id): JsonResponse
    {
        $user = $request->user();

        if ($user->tipo_usuario !== 'empresa') {
            return response()->json(['message' => 'Acceso denegado'], 403);
        }

        /*
         * Código Futuro Real:
         * $place = $user->placesManaged()->findOrFail($id);
         * $schedule = $place->schedules()->create($request->all());
         */
        
        return response()->json([
            'message' => 'Horario creado correctamente (Simulación)',
            'id' => rand(1000, 9999)
        ], 201);
    }

    /**
     * Update an existing schedule (Mocked).
     */
    public function updateSchedule(Request $request, $id, $scheduleId): JsonResponse
    {
        $user = $request->user();

        if ($user->tipo_usuario !== 'empresa') {
            return response()->json(['message' => 'Acceso denegado'], 403);
        }

        /*
         * Código Futuro Real:
         * $schedule = PlaceSchedule::where('place_id', $id)->findOrFail($scheduleId);
         * $schedule->update($request->all());
         */

        return response()->json([
            'message' => 'Horario actualizado correctamente (Simulación)'
        ], 200);
    }

    /**
     * Remove a schedule (Mocked).
     */
    public function destroySchedule(Request $request, $id, $scheduleId): JsonResponse
    {
        $user = $request->user();

        if ($user->tipo_usuario !== 'empresa') {
            return response()->json(['message' => 'Acceso denegado'], 403);
        }

        /*
         * Código Futuro Real:
         * $schedule = PlaceSchedule::where('place_id', $id)->findOrFail($scheduleId);
         * $schedule->delete();
         */

        return response()->json([
            'message' => 'Horario eliminado correctamente (Simulación)'
        ], 200);
    }

    /**
     * Get reservation statistics (Mocked).
     */
    public function getReservationStats(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->tipo_usuario !== 'empresa') {
            return response()->json(['message' => 'Acceso denegado'], 403);
        }

        $stats = [
            'pending' => 5,
            'accepted' => 12,
            'rejected' => 2,
        ];

        return response()->json($stats, 200);
    }
}
