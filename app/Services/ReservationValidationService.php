<?php

namespace App\Services;

use App\Models\Reservation;
use App\Models\Place;
use Carbon\Carbon;

class ReservationValidationService
{
    /**
     * Validar si hay superposición de reservas
     * Retorna: ['isValid' => bool, 'message' => string]
     */
    public static function checkOverlap(
        Place $place,
        $fechaVisita,
        $horaVisita,
        $duracionMinutos = 120,
        ?int $excludeReservationId = null
    ): array {
        // Convertir inputs a objetos Carbon/datetime
        $fechaInicio = Carbon::createFromFormat('Y-m-d H:i', $fechaVisita . ' ' . $horaVisita);
        $fechaFin = $fechaInicio->copy()->addMinutes($duracionMinutos);

        // Obtener reservas aceptadas en esa fecha
        $query = Reservation::where('place_id', $place->id)
            ->where('fecha_visita', $fechaVisita)
            ->where('estado', 'aceptada');

        // Excluir la reserva si se proporciona
        if ($excludeReservationId) {
            $query->where('id', '!=', $excludeReservationId);
        }

        $reservaciones = $query->get();

        foreach ($reservaciones as $reserva) {
            $reservaInicio = Carbon::createFromFormat('Y-m-d H:i', $reserva->fecha_visita . ' ' . $reserva->hora_visita);
            $reservaFin = $reservaInicio->copy()->addMinutes($duracionMinutos);

            // Verificar si hay superposición
            if ($fechaInicio < $reservaFin && $fechaFin > $reservaInicio) {
                return [
                    'isValid' => false,
                    'message' => 'Ya existe una reserva en ese horario. Intente con otra hora.',
                ];
            }
        }

        return [
            'isValid' => true,
            'message' => 'Horario disponible.',
        ];
    }

    /**
     * Validar disponibilidad según horarios del lugar
     * Retorna: ['isValid' => bool, 'message' => string]
     */
    public static function checkAvailabilityBySchedule(Place $place, $fechaVisita, $horaVisita): array
    {
        // Si no hay horarios registrados, aceptar por defecto
        if ($place->schedules()->count() === 0) {
            return [
                'isValid' => false,
                'message' => 'Este lugar no tiene horarios configurados. Contacte con el administrador.',
            ];
        }

        // Obtener día de la semana en español
        $diaNumero = Carbon::createFromFormat('Y-m-d', $fechaVisita)->dayOfWeek;
        $diasSemana = [
            0 => 'domingo',
            1 => 'lunes',
            2 => 'martes',
            3 => 'miercoles',
            4 => 'jueves',
            5 => 'viernes',
            6 => 'sabado',
        ];
        $diaSemana = $diasSemana[$diaNumero];

        // Buscar horarios para ese día
        $horarios = $place->schedules()
            ->where('dia_semana', $diaSemana)
            ->where('activo', true)
            ->get();

        if ($horarios->isEmpty()) {
            return [
                'isValid' => false,
                'message' => "Este lugar está cerrado los $diaSemana.",
            ];
        }

        // Verificar que la hora esté dentro de algún rango de horario
        $horaVisitaTime = Carbon::createFromFormat('H:i', $horaVisita);

        foreach ($horarios as $horario) {
            $horaAperturaTime = Carbon::createFromFormat('H:i', $horario->hora_apertura);
            $horaCierreTime = Carbon::createFromFormat('H:i', $horario->hora_cierre);

            if ($horaVisitaTime >= $horaAperturaTime && $horaVisitaTime < $horaCierreTime) {
                return [
                    'isValid' => true,
                    'message' => 'Disponible.',
                ];
            }
        }

        return [
            'isValid' => false,
            'message' => 'El lugar no está abierto a esa hora. Horarios: ' .
                $horarios->map(fn($h) => "{$h->hora_apertura} - {$h->hora_cierre}")->implode(', '),
        ];
    }

    /**
     * Validar capacidad del lugar
     * Retorna: ['isValid' => bool, 'message' => string]
     */
    public static function checkCapacity(Place $place, int $personas): array
    {
        // Si no hay capacidad configurada, aceptar
        if (!$place->capacidad_maxima) {
            return [
                'isValid' => true,
                'message' => 'Capacidad verificada.',
            ];
        }

        if ($personas > $place->capacidad_maxima) {
            return [
                'isValid' => false,
                'message' => "La capacidad máxima es {$place->capacidad_maxima} personas.",
            ];
        }

        return [
            'isValid' => true,
            'message' => 'Capacidad disponible.',
        ];
    }

    /**
     * Realizar todas las validaciones
     * Retorna: ['isValid' => bool, 'errors' => array]
     */
    public static function validateAll(
        Place $place,
        $fechaVisita,
        $horaVisita,
        int $personas,
        ?int $excludeReservationId = null
    ): array {
        $errors = [];

        // Validar disponibilidad por horario
        $scheduleValidation = self::checkAvailabilityBySchedule($place, $fechaVisita, $horaVisita);
        if (!$scheduleValidation['isValid']) {
            $errors['horario'] = $scheduleValidation['message'];
        }

        // Validar superposición
        $overlapValidation = self::checkOverlap($place, $fechaVisita, $horaVisita, excludeReservationId: $excludeReservationId);
        if (!$overlapValidation['isValid']) {
            $errors['solapamiento'] = $overlapValidation['message'];
        }

        // Validar capacidad
        $capacityValidation = self::checkCapacity($place, $personas);
        if (!$capacityValidation['isValid']) {
            $errors['capacidad'] = $capacityValidation['message'];
        }

        return [
            'isValid' => count($errors) === 0,
            'errors' => $errors,
        ];
    }
}
