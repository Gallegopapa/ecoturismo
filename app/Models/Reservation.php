<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $table = 'reservations';

    protected $fillable = [
        'user_id',
        'place_id',
        'fecha',
        'fecha_reserva',
        'fecha_visita',
        'hora_visita',
        'personas',
        'telefono_contacto',
        'comentarios',
        'precio_total',
        'estado',
    ];

    protected $casts = [
        'fecha' => 'date',
        'fecha_reserva' => 'datetime',
        'fecha_visita' => 'date',
        'hora_visita' => 'string', // Mantener como string para formato HH:mm
        'precio_total' => 'decimal:2',
    ];

    /**
     * Relación: Una reserva pertenece a un usuario
     */
    public function usuario()
    {
        return $this->belongsTo(Usuarios::class, 'user_id');
    }

    /**
     * Relación: Una reserva pertenece a un lugar
     */
    public function place()
    {
        return $this->belongsTo(Place::class, 'place_id');
    }

    /**
     * Relación: Una reserva puede tener un pago
     */
    public function payment()
    {
        return $this->hasOne(Payment::class, 'reservation_id');
    }

    /**
     * Relación: Una reserva tiene una respuesta de la empresa
     */
    public function companyReservation()
    {
        return $this->hasOne(CompanyReservation::class, 'reservation_id');
    }
}