<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Place;
use App\Models\Reservation;
use App\Models\Usuarios;
use App\Models\Review;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_places' => Place::count(),
            'total_users' => Usuarios::count(),
            'total_reservations' => Reservation::count(),
            'pending_reservations' => Reservation::where('estado', 'pendiente')->count(),
            'confirmed_reservations' => Reservation::where('estado', 'confirmada')->count(),
            'total_reviews' => Review::count(),
        ];

        $recent_reservations = Reservation::with(['place', 'usuario'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $popular_places = Place::withCount('reservations')
            ->orderBy('reservations_count', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_reservations', 'popular_places'));
    }
}
