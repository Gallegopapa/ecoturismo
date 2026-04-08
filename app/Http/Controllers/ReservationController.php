<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function index()
    {
        // El middleware 'auth' ya verifica la autenticación, pero agregamos verificación adicional
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para ver tus reservas.');
        }

        $reservations = Reservation::where('user_id', $user->id)
            ->with(['place'])
            ->orderBy('fecha_visita', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('reservations.index', compact('reservations'));
    }

    public function create(Place $place)
    {
        return view('reservations.create', compact('place'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'place_id' => 'required|exists:places,id',
            'fecha_visita' => 'required|date|after_or_equal:today',
            'hora_visita' => 'required|date_format:H:i',
            'personas' => 'required|integer|min:1|max:50',
            'telefono_contacto' => 'nullable|string|max:20',
            'comentarios' => 'nullable|string',
            'precio_total' => 'nullable|numeric|min:0',
        ]);

        // Obtener el lugar
        $place = Place::findOrFail($data['place_id']);

        // Validar que el día esté disponible (verificar horarios del lugar)
        $dayOfWeek = strtolower(date('l', strtotime($data['fecha_visita'])));
        $diasSemana = [
            'monday' => 'lunes',
            'tuesday' => 'martes',
            'wednesday' => 'miercoles',
            'thursday' => 'jueves',
            'friday' => 'viernes',
            'saturday' => 'sabado',
            'sunday' => 'domingo',
        ];
        $diaSemana = $diasSemana[$dayOfWeek] ?? null;

        $daySchedules = $place->activeSchedules()
            ->where('dia_semana', $diaSemana)
            ->get();

        if ($daySchedules->isEmpty()) {
            return back()->withErrors([
                'fecha_visita' => 'El lugar está cerrado el ' . ucfirst($diaSemana) . '. Por favor, selecciona otro día.'
            ])->withInput();
        }

        // Validar que la hora esté dentro de los horarios disponibles del día
        $horaValida = false;
        foreach ($daySchedules as $schedule) {
            if ($data['hora_visita'] >= $schedule->hora_inicio && $data['hora_visita'] < $schedule->hora_fin) {
                $horaValida = true;
                break;
            }
        }

        if (!$horaValida) {
            $horariosDisponibles = $daySchedules->map(function($s) {
                return $s->hora_inicio . ' - ' . $s->hora_fin;
            })->implode(', ');
            
            return back()->withErrors([
                'hora_visita' => 'La hora seleccionada no está disponible. Horarios disponibles: ' . $horariosDisponibles
            ])->withInput();
        }

        // Validar conflictos de 2 horas (cada reserva dura 2 horas)
        // Normalizar formato de hora (tomar solo HH:MM)
        $horaReservaStr = substr($data['hora_visita'], 0, 5);
        $horaReserva = \Carbon\Carbon::createFromFormat('H:i', $horaReservaStr);
        $horaInicioReserva = $horaReserva->copy();
        $horaFinReserva = $horaReserva->copy()->addHours(2);

        $conflictingReservations = Reservation::where('place_id', $data['place_id'])
            ->whereDate('fecha_visita', $data['fecha_visita'])
            ->where('estado', '!=', 'cancelada')
            ->get()
            ->filter(function($reservation) use ($horaInicioReserva, $horaFinReserva) {
                // Normalizar formato de hora (tomar solo HH:MM)
                $horaExistenteStr = substr($reservation->hora_visita, 0, 5);
                $horaExistente = \Carbon\Carbon::createFromFormat('H:i', $horaExistenteStr);
                $horaInicioExistente = $horaExistente->copy();
                $horaFinExistente = $horaExistente->copy()->addHours(2);

                // Verificar si hay solapamiento
                return ($horaInicioReserva->lt($horaFinExistente) && $horaFinReserva->gt($horaInicioExistente));
            });

        if ($conflictingReservations->count() > 0) {
            $conflictingReservation = $conflictingReservations->first();
            // Normalizar formato de hora (tomar solo HH:MM)
            $horaExistenteStr = substr($conflictingReservation->hora_visita, 0, 5);
            $horaExistente = \Carbon\Carbon::createFromFormat('H:i', $horaExistenteStr);
            
            // Generar sugerencias: 2 horas antes y 2 horas después
            $sugerenciaAntes = $horaExistente->copy()->subHours(2);
            $sugerenciaDespues = $horaExistente->copy()->addHours(2);
            
            $suggestions = [];
            
            // Verificar que las sugerencias estén dentro de los horarios del lugar
            foreach ($daySchedules as $schedule) {
                // Normalizar formato de hora (tomar solo HH:MM)
                $horaInicioScheduleStr = substr($schedule->hora_inicio, 0, 5);
                $horaFinScheduleStr = substr($schedule->hora_fin, 0, 5);
                $horaInicioSchedule = \Carbon\Carbon::createFromFormat('H:i', $horaInicioScheduleStr);
                $horaFinSchedule = \Carbon\Carbon::createFromFormat('H:i', $horaFinScheduleStr);
                
                // Sugerencia 2 horas antes
                if ($sugerenciaAntes->gte($horaInicioSchedule) && 
                    $sugerenciaAntes->copy()->addHours(2)->lte($horaFinSchedule)) {
                    $suggestions[] = $sugerenciaAntes->format('H:i') . ' (2 horas antes)';
                }
                
                // Sugerencia 2 horas después
                if ($sugerenciaDespues->gte($horaInicioSchedule) && 
                    $sugerenciaDespues->copy()->addHours(2)->lte($horaFinSchedule)) {
                    $suggestions[] = $sugerenciaDespues->format('H:i') . ' (2 horas después)';
                }
            }

            $mensajeError = 'Ya existe una reserva que se solapa con el horario seleccionado. Cada reserva tiene una duración de 2 horas.';
            if (count($suggestions) > 0) {
                $mensajeError .= ' Horarios sugeridos: ' . implode(', ', $suggestions);
            }

            return back()->withErrors([
                'hora_visita' => $mensajeError
            ])->withInput();
        }

        $reservation = Reservation::create([
            'user_id' => Auth::id(),
            'place_id' => $data['place_id'],
            'fecha_reserva' => now(),
            'fecha_visita' => $data['fecha_visita'],
            'hora_visita' => $data['hora_visita'],
            'fecha' => $data['fecha_visita'], // Mantener compatibilidad
            'personas' => $data['personas'],
            'telefono_contacto' => $data['telefono_contacto'] ?? null,
            'comentarios' => $data['comentarios'] ?? null,
            'precio_total' => $data['precio_total'] ?? null,
            'estado' => 'pendiente',
        ]);

        return redirect()->route('web.reservations.index')->with('status', 'Reserva creada correctamente.');
    }

    public function destroy(Reservation $reservation)
    {
        if ($reservation->user_id !== Auth::id()) {
            abort(403);
        }

        $reservation->delete();
        return redirect()->route('web.reservations.index')->with('status', 'Reserva cancelada.');
    }
}
