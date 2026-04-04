<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyReservation extends Model
{
    use HasFactory;

    protected $table = 'company_reservations';

    protected $fillable = [
        'reservation_id',
        'company_user_id',
        'place_id',
        'rejection_reason_id',
        'estado',
        'comentario_rechazo',
        'fecha_respuesta',
    ];

    protected $casts = [
        'fecha_respuesta' => 'datetime',
    ];

    /**
     * Relación: Una respuesta de empresa pertenece a una reserva
     */
    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id');
    }

    /**
     * Relación: Una respuesta de empresa fue creada por un usuario empresa
     */
    public function companyUser()
    {
        return $this->belongsTo(Usuarios::class, 'company_user_id');
    }

    /**
     * Relación: Una respuesta de empresa pertenece a un lugar
     */
    public function place()
    {
        return $this->belongsTo(Place::class, 'place_id');
    }

    /**
     * Relación: Si fue rechazada, tiene una razón de rechazo
     */
    public function rejectionReason()
    {
        return $this->belongsTo(RejectionReason::class, 'rejection_reason_id');
    }

    /**
     * Verificar si está pendiente
     */
    public function isPending(): bool
    {
        return $this->estado === 'pendiente';
    }

    /**
     * Verificar si fue aceptada
     */
    public function isAccepted(): bool
    {
        return $this->estado === 'aceptada';
    }

    /**
     * Verificar si fue rechazada
     */
    public function isRejected(): bool
    {
        return $this->estado === 'rechazada';
    }

    /**
     * Marcar como aceptada
     */
    public function accept(): void
    {
        $this->update([
            'estado' => 'aceptada',
            'fecha_respuesta' => now(),
        ]);
    }

    /**
     * Marcar como rechazada
     */
    public function reject($rejectionReasonId = null, $comentario = null): void
    {
        $this->update([
            'estado' => 'rechazada',
            'rejection_reason_id' => $rejectionReasonId,
            'comentario_rechazo' => $comentario,
            'fecha_respuesta' => now(),
        ]);
    }

    /**
     * Reabrir una reserva rechazada
     */
    public function reopen(): void
    {
        $this->update([
            'estado' => 'pendiente',
            'rejection_reason_id' => null,
            'comentario_rechazo' => null,
            'fecha_respuesta' => null,
        ]);
    }
}
