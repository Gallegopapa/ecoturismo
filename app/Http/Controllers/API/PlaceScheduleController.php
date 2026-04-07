<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Place;
use App\Models\PlaceSchedule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PlaceScheduleController extends Controller
{
    /**
     * Obtener todos los horarios de un lugar (público - solo horarios activos)
     */
    public function index(Place $place): JsonResponse
    {
        $schedules = $place->schedules()
            ->where('activo', true)
            ->orderByRaw("
                CASE dia_semana
                    WHEN 'lunes' THEN 1
                    WHEN 'martes' THEN 2
                    WHEN 'miercoles' THEN 3
                    WHEN 'jueves' THEN 4
                    WHEN 'viernes' THEN 5
                    WHEN 'sabado' THEN 6
                    WHEN 'domingo' THEN 7
                END
            ")
            ->orderBy('hora_inicio')
            ->get();

        return response()->json($schedules);
    }

    /**
     * Crear un nuevo horario para un lugar
     */
    public function store(Request $request, Place $place): JsonResponse
    {
        $data = $request->validate([
            'dia_semana' => 'required|in:lunes,martes,miercoles,jueves,viernes,sabado,domingo',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'activo' => 'nullable|boolean',
        ], [
            'dia_semana.required' => 'El día de la semana es requerido.',
            'dia_semana.in' => 'El día de la semana no es válido.',
            'hora_inicio.required' => 'La hora de inicio es requerida.',
            'hora_inicio.date_format' => 'La hora de inicio debe tener el formato HH:mm.',
            'hora_fin.required' => 'La hora de fin es requerida.',
            'hora_fin.date_format' => 'La hora de fin debe tener el formato HH:mm.',
            'hora_fin.after' => 'La hora de fin debe ser posterior a la hora de inicio.',
        ]);

        $schedule = $place->schedules()->create([
            'dia_semana' => $data['dia_semana'],
            'hora_inicio' => $data['hora_inicio'],
            'hora_fin' => $data['hora_fin'],
            'activo' => $data['activo'] ?? true,
        ]);

        return response()->json([
            'message' => 'Horario creado correctamente.',
            'schedule' => $schedule
        ], 201);
    }

    /**
     * Actualizar un horario
     */
    public function update(Request $request, Place $place, PlaceSchedule $schedule): JsonResponse
    {
        // Verificar que el horario pertenece al lugar
        if ($schedule->place_id !== $place->id) {
            return response()->json(['message' => 'El horario no pertenece a este lugar.'], 422);
        }

        $data = $request->validate([
            'dia_semana' => 'sometimes|in:lunes,martes,miercoles,jueves,viernes,sabado,domingo',
            'hora_inicio' => 'sometimes|date_format:H:i',
            'hora_fin' => 'sometimes|date_format:H:i',
            'activo' => 'nullable|boolean',
        ], [
            'dia_semana.in' => 'El día de la semana no es válido.',
            'hora_inicio.date_format' => 'La hora de inicio debe tener el formato HH:mm.',
            'hora_fin.date_format' => 'La hora de fin debe tener el formato HH:mm.',
        ]);

        // Validar que hora_fin sea posterior a hora_inicio si ambas están presentes
        if (isset($data['hora_inicio']) && isset($data['hora_fin'])) {
            if (strtotime($data['hora_fin']) <= strtotime($data['hora_inicio'])) {
                return response()->json([
                    'message' => 'La hora de fin debe ser posterior a la hora de inicio.',
                    'errors' => ['hora_fin' => ['La hora de fin debe ser posterior a la hora de inicio.']]
                ], 422);
            }
        } elseif (isset($data['hora_fin'])) {
            // Solo se actualiza hora_fin, validar contra la hora_inicio actual
            if (strtotime($data['hora_fin']) <= strtotime($schedule->hora_inicio)) {
                return response()->json([
                    'message' => 'La hora de fin debe ser posterior a la hora de inicio.',
                    'errors' => ['hora_fin' => ['La hora de fin debe ser posterior a la hora de inicio.']]
                ], 422);
            }
        } elseif (isset($data['hora_inicio'])) {
            // Solo se actualiza hora_inicio, validar contra la hora_fin actual
            if (strtotime($schedule->hora_fin) <= strtotime($data['hora_inicio'])) {
                return response()->json([
                    'message' => 'La hora de fin debe ser posterior a la hora de inicio.',
                    'errors' => ['hora_inicio' => ['La hora de inicio debe ser anterior a la hora de fin.']]
                ], 422);
            }
        }

        $schedule->update($data);

        return response()->json([
            'message' => 'Horario actualizado correctamente.',
            'schedule' => $schedule
        ]);
    }

    /**
     * Eliminar un horario
     */
    public function destroy(Place $place, PlaceSchedule $schedule): JsonResponse
    {
        // Verificar que el horario pertenece al lugar
        if ($schedule->place_id !== $place->id) {
            return response()->json(['message' => 'El horario no pertenece a este lugar.'], 422);
        }

        $schedule->delete();

        return response()->json([
            'message' => 'Horario eliminado correctamente.'
        ]);
    }
}
