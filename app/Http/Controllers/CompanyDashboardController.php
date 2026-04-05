<?php

namespace App\Http\Controllers;

use App\Models\Usuarios;
use App\Models\CompanyReservation;
use App\Models\RejectionReason;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyDashboardController extends Controller
{
    /**
     * Mostrar el dashboard de la empresa
     */
    public function index()
    {
        $user = Auth::user();

        // Verificar que sea usuario empresa
        if (!$user || $user->tipo_usuario !== 'empresa') {
            return redirect('/')->with('error', 'No tienes permisos para acceder a este panel');
        }

        // Obtener lugares asignados
        $placesManaged = $user->placesManaged()->get();

        if ($placesManaged->isEmpty()) {
            return view('company.dashboard', [
                'user' => $user,
                'reservations' => [],
                'stats' => ['pendientes' => 0, 'aceptadas' => 0, 'rechazadas' => 0],
                'rejectionReasons' => [],
                'placesManaged' => $placesManaged
            ])->with('warning', 'No tienes lugares asignados');
        }

        // Obtener IDs de lugares
        $placeIds = $placesManaged->pluck('id')->toArray();

        // Obtener reservas de estos lugares
        $reservations = CompanyReservation::whereIn('place_id', $placeIds)
            ->with('reservation.usuario', 'reservation.place', 'rejectionReason')
            ->orderBy('created_at', 'desc')
            ->get();

        // Calcular estadísticas
        $stats = [
            'pendientes' => $reservations->where('estado', 'pendiente')->count(),
            'aceptadas' => $reservations->where('estado', 'aceptada')->count(),
            'rechazadas' => $reservations->where('estado', 'rechazada')->count(),
            'total' => $reservations->count(),
        ];

        // Obtener razones de rechazo
        $rejectionReasons = RejectionReason::all();

        return view('company.dashboard', compact('user', 'reservations', 'stats', 'rejectionReasons', 'placesManaged'));
    }

    /**
     * Aceptar una reserva
     */
    public function accept($id)
    {
        $user = Auth::user();

        if ($user->tipo_usuario !== 'empresa') {
            return redirect()->back()->with('error', 'No tienes permisos');
        }

        $companyReservation = CompanyReservation::find($id);

        if (!$companyReservation) {
            return redirect()->back()->with('error', 'Reserva no encontrada');
        }

        // Verificar que pertenece a un lugar que gestiona
        $placeIds = $user->placesManaged()->pluck('id')->toArray();
        if (!in_array($companyReservation->place_id, $placeIds)) {
            return redirect()->back()->with('error', 'No tienes acceso a esta reserva');
        }

        // Aceptar
        $companyReservation->accept();

        return redirect()->back()->with('success', 'Reserva aceptada correctamente');
    }

    /**
     * Rechazar una reserva
     */
    public function reject(Request $request, $id)
    {
        $user = Auth::user();

        if ($user->tipo_usuario !== 'empresa') {
            return redirect()->back()->with('error', 'No tienes permisos');
        }

        $companyReservation = CompanyReservation::find($id);

        if (!$companyReservation) {
            return redirect()->back()->with('error', 'Reserva no encontrada');
        }

        // Verificar que pertenece a un lugar que gestiona
        $placeIds = $user->placesManaged()->pluck('id')->toArray();
        if (!in_array($companyReservation->place_id, $placeIds)) {
            return redirect()->back()->with('error', 'No tienes acceso a esta reserva');
        }

        // Validar datos
        $request->validate([
            'rejection_reason_id' => 'required|exists:rejection_reasons,id',
            'comentario' => 'nullable|string|max:1000',
        ]);

        // Rechazar
        $companyReservation->reject($request->input('rejection_reason_id'), $request->input('comentario'));

        return redirect()->back()->with('success', 'Reserva rechazada correctamente');
    }
}
