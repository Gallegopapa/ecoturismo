<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RejectionReason extends Model
{
    use HasFactory;

    protected $table = 'rejection_reasons';

    protected $fillable = [
        'code',
        'descripcion',
        'detalles',
    ];

    /**
     * Relación: Una razón de rechazo puede estar en muchas respuestas de empresa
     */
    public function companyReservations()
    {
        return $this->hasMany(CompanyReservation::class, 'rejection_reason_id');
    }
}
