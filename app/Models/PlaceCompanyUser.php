<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlaceCompanyUser extends Model
{
    use HasFactory;

    protected $table = 'place_company_users';

    protected $fillable = [
        'place_id',
        'company_user_id',
        'es_principal',
    ];

    protected $casts = [
        'es_principal' => 'boolean',
    ];

    /**
     * Relación: Una asignación pertenece a un lugar
     */
    public function place()
    {
        return $this->belongsTo(Place::class, 'place_id');
    }

    /**
     * Relación: Una asignación pertenece a un usuario empresa
     */
    public function companyUser()
    {
        return $this->belongsTo(Usuarios::class, 'company_user_id');
    }
}
