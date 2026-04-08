<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationAdminController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with(['place', 'usuario'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.reservations', compact('reservations'));
    }

    public function update(Request $request, Reservation $reservation)
    {
        $data = $request->validate([
            'estado' => 'required|in:pendiente,confirmada,cancelada',
            'fecha_visita' => 'sometimes|date',
            'hora_visita' => 'nullable|date_format:H:i',
            'personas' => 'sometimes|integer|min:1',
            'comentarios' => 'nullable|string',
        ]);

        $reservation->update($data);

        return redirect()->route('admin.reservations.index')->with('status', 'Reserva actualizada.');
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        return redirect()->route('admin.reservations.index')->with('status', 'Reserva eliminada.');
    }
}
