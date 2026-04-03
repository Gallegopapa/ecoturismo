<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'place_id',
        'ecohotel_id',
        'rating',
        'comment',
        'fecha_comentario',
    ];

    protected $casts = [
        'fecha_comentario' => 'datetime',
        'rating' => 'integer',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuarios::class, 'user_id');
    }

    public function place()
    {
        return $this->belongsTo(Place::class, 'place_id');
    }

    public function ecohotel()
    {
        return $this->belongsTo(Ecohotel::class, 'ecohotel_id');
    }
}
