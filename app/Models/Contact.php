<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $table = 'contacts';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'message',
        'user_id',
    ];

    /**
     * Relación: Un contacto puede pertenecer a un usuario (opcional)
     */
    public function usuario()
    {
        return $this->belongsTo(Usuarios::class, 'user_id');
    }
}
