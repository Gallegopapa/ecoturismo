<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Ecohotel extends Model
{
    use HasFactory;

    /**
     * Reseñas directas de ecohotel
     */
    public function reviews()
    {
        return $this->hasMany(\App\Models\Review::class, 'ecohotel_id');
    }

    protected $fillable = [
        'name',
        'description',
        'location',
        'image',
        'latitude',
        'longitude',
        'telefono',
        'email',
        'sitio_web',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    /**
     * Accessor para image - devuelve ruta normalizada y validada
     * Prioridad: /imagenes/ > /storage/ecohotels/ > null
     */
    public function getImageAttribute($value)
    {
        if (!$value) {
            return null;
        }

        $value = trim((string) $value);
        if (empty($value)) {
            return null;
        }

        // Si ya es una URL completa (http/https)
        if (preg_match('/^https?:\/\//', $value)) {
            return $value;
        }

        // Si comienza con /storage/ o storage/
        if (strpos($value, '/storage/') === 0 || strpos($value, 'storage/') === 0) {
            $cleanPath = str_replace(['storage/', '/storage/'], '', $value);
            return Storage::disk('public')->url(ltrim($cleanPath, '/'));
        }

        // Si comienza con /imagenes/ o imagenes/
        if (strpos($value, '/imagenes/') === 0 || strpos($value, 'imagenes/') === 0) {
            $path = ltrim($value, '/');
            return asset($path);
        }

        // Si es una ruta relativa de ecohoteles (ej: ecohotels/foo.jpg)
        if (strpos($value, 'ecohotels/') === 0) {
            return Storage::disk('public')->url($value);
        }

        // Fallback: Si no tiene barra inicial, asumir /imagenes/
        return asset('imagenes/' . ltrim($value, '/'));
    }

    /**
     * Relación muchos a muchos con categorías
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_ecohotel');
    }

    /**
     * Relación muchos a muchos con lugares turísticos
     */
    public function places()
    {
        return $this->belongsToMany(Place::class, 'ecohotel_place');
    }
}
