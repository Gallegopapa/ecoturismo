<?php

namespace App\Http\Controllers;

use App\Models\Place;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlaceController extends Controller
{
    /**
     * Mostrar todos los lugares
     */
    public function index()
    {
        $places = Place::orderBy('id', 'desc')->get();
        return view('lugares', compact('places'));
    }

    /**
     * Mostrar un lugar específico
     */
    public function show(Place $place)
    {
        $reviews = Review::where('place_id', $place->id)
            ->with('usuario:id,name')
            ->orderBy('fecha_comentario', 'desc')
            ->get();
        
        $averageRating = $reviews->avg('rating') ?? 0;
        $userReview = null;
        
        if (Auth::check()) {
            $userReview = Review::where('user_id', Auth::id())
                ->where('place_id', $place->id)
                ->first();
        }

        // Cargar horarios activos del lugar
        $schedules = $place->activeSchedules()->orderByRaw("
            CASE dia_semana
                WHEN 'lunes' THEN 1
                WHEN 'martes' THEN 2
                WHEN 'miercoles' THEN 3
                WHEN 'jueves' THEN 4
                WHEN 'viernes' THEN 5
                WHEN 'sabado' THEN 6
                WHEN 'domingo' THEN 7
            END
        ")->orderBy('hora_inicio')->get();

        // Cargar reservas futuras del lugar (para mostrar horarios ocupados)
        $reservations = \App\Models\Reservation::where('place_id', $place->id)
            ->where('fecha_visita', '>=', now()->toDateString())
            ->where('estado', '!=', 'cancelada')
            ->orderBy('fecha_visita')
            ->orderBy('hora_visita')
            ->get();
        
        return view('place.show', compact('place', 'reviews', 'averageRating', 'userReview', 'schedules', 'reservations'));
    }
}

