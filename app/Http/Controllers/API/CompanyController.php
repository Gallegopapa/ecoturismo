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

        return response()->json([ 'message' => 'Lugar actualizado correctamente (Simulación)', 'updated_id' => $id ], 200);
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

        $schedules = [
            [ 'id' => 101, 'place_id' => $id, 'dia_semana' => 'lunes', 'hora_inicio' => '08:00', 'hora_fin' => '17:00', 'activo' => true ],
            [ 'id' => 102, 'place_id' => $id, 'dia_semana' => 'martes', 'hora_inicio' => '08:00', 'hora_fin' => '17:00', 'activo' => true ]
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

        return response()->json([ 'message' => 'Horario creado correctamente (Simulación)', 'id' => rand(1000, 9999) ], 201);
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

        return response()->json([ 'message' => 'Horario actualizado correctamente (Simulación)' ], 200);
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

        return response()->json([ 'message' => 'Horario eliminado correctamente (Simulación)' ], 200);
    }

    /**
     * Get reservations for company places (Mocked).
     */
    public function getReservations(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->tipo_usuario !== 'empresa') {
            return response()->json(['message' => 'Acceso denegado'], 403);
        }

        $reservations = [
            [ 'id' => 1, 'place_name' => 'Reserva Natural La Pastora', 'client_name' => 'Juan Pérez', 'email' => 'juan.perez@gmail.com', 'phone' => '+57 321 000 0000', 'fecha_visita' => '2025-05-15', 'hora_visita' => '10:00', 'personas' => 4, 'status' => 'pending' ],
            [ 'id' => 2, 'place_name' => 'Reserva Natural La Pastora', 'client_name' => 'María García', 'email' => 'maria.garcia@gmail.com', 'phone' => '+57 310 999 9999', 'fecha_visita' => '2025-05-16', 'hora_visita' => '14:30', 'personas' => 2, 'status' => 'accepted' ]
        ];

        return response()->json($reservations, 200);
    }

    /**
     * Accept a reservation (Mocked).
     */
    public function acceptReservation(Request $request, $id): JsonResponse
    {
        $user = $request->user();

        if ($user->tipo_usuario !== 'empresa') {
            return response()->json(['message' => 'Acceso denegado'], 403);
        }

        return response()->json([ 'message' => 'Reservación aceptada con éxito (Simulación)' ], 200);
    }

    /**
     * Reject a reservation with reason (Mocked).
     */
    public function rejectReservation(Request $request, $id): JsonResponse
    {
        $user = $request->user();

        if ($user->tipo_usuario !== 'empresa') {
            return response()->json(['message' => 'Acceso denegado'], 403);
        }

        return response()->json([ 'message' => 'Reservación rechazada con éxito (Simulación)' ], 200);
    }

    /**
     * Reopen a rejected reservation (Mocked).
     */
    public function reopenReservation(Request $request, $id): JsonResponse
    {
        $user = $request->user();

        if ($user->tipo_usuario !== 'empresa') {
            return response()->json(['message' => 'Acceso denegado'], 403);
        }

        return response()->json([ 'message' => 'Reservación reabierta con éxito (Simulación)' ], 200);
    }

    /**
     * Get rejection reasons (Mocked/Static).
     */
    public function getRejectionReasons(): JsonResponse
    {
        $reasons = [
            ['id' => 1, 'label' => 'El lugar está lleno'],
            ['id' => 2, 'label' => 'Mantenimiento en las instalaciones'],
            ['id' => 3, 'label' => 'Clima adverso'],
            ['id' => 4, 'label' => 'Evento privado'],
            ['id' => 5, 'label' => 'Otro (especificar)']
        ];

        return response()->json($reasons, 200);
    }

    /**
     * Get reservation statistics (Global company stats - Mocked).
     */
    public function getReservationStats(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->tipo_usuario !== 'empresa') {
            return response()->json(['message' => 'Acceso denegado'], 403);
        }

        /*
         * Código Futuro Real:
         * $placeIds = $user->placesManaged()->pluck('id');
         * $stats = [
         *     'pending' => Reservation::whereIn('place_id', $placeIds)->where('status', 'pending')->count(),
         *     'accepted' => Reservation::whereIn('place_id', $placeIds)->where('status', 'accepted')->count(),
         *     'rejected' => Reservation::whereIn('place_id', $placeIds)->where('status', 'rejected')->count(),
         * ];
         */
        $stats = [ 'pending' => 8, 'accepted' => 24, 'rejected' => 5 ];

        return response()->json($stats, 200);
    }

    /**
     * Get reservation statistics for a specific place (Mocked - AC2).
     */
    public function getPlaceReservationStats(Request $request, $placeId): JsonResponse
    {
        $user = $request->user();

        if ($user->tipo_usuario !== 'empresa') {
            return response()->json(['message' => 'Acceso denegado'], 403);
        }

        /*
         * Código Futuro Real (AC3 Access Control):
         * $place = $user->placesManaged()->findOrFail($placeId);
         */
        
        $stats = [ 'pending' => 4, 'accepted' => 12, 'rejected' => 3 ];

        return response()->json($stats, 200);
    }
}
