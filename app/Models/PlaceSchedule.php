<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PlaceSchedule extends Model
{
    use HasFactory;

    protected $table = 'place_schedules';

    protected $fillable = [
        'place_id',
        'dia_semana',
        'hora_inicio',
        'hora_fin',
        'activo',
    ];

    protected $casts = [
        'hora_inicio' => 'string', // Formato HH:mm
        'hora_fin' => 'string', // Formato HH:mm
        'activo' => 'boolean',
    ];

    /**
     * Relación: Un horario pertenece a un lugar
     */
    public function place()
    {
        return $this->belongsTo(Place::class, 'place_id');
    }

    /**
     * Verificar si un horario está disponible para una fecha y hora específica
     */
    public function isAvailableFor($fecha, $hora)
    {
        if (!$this->activo) {
            return false;
        }

        // Obtener el día de la semana de la fecha (0 = domingo, 1 = lunes, etc.)
        $diaNumero = date('w', strtotime($fecha));
        $diasSemana = [
            0 => 'domingo',
            1 => 'lunes',
            2 => 'martes',
            3 => 'miercoles',
            4 => 'jueves',
            5 => 'viernes',
            6 => 'sabado',
        ];

        $diaFecha = $diasSemana[$diaNumero];

        // Verificar que el día coincida
        if ($this->dia_semana !== $diaFecha) {
            return false;
        }

        // Verificar que la hora esté dentro del rango
        // Convertir horas a segundos desde medianoche para comparar
        $horaInicio = $this->timeToSeconds($this->hora_inicio);
        $horaFin = $this->timeToSeconds($this->hora_fin);
        $horaReserva = $this->timeToSeconds($hora);

        return $horaReserva >= $horaInicio && $horaReserva <= $horaFin;
    }

    /**
     * Convertir hora en formato HH:mm a segundos desde medianoche
     */
    private function timeToSeconds($time)
    {
        $parts = explode(':', $time);
        return (int)$parts[0] * 3600 + (int)$parts[1] * 60;
    }
}
