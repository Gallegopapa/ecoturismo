<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Rules\NoProfanity;
use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\CompanyReservation;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use App\Models\Place;

class ReservationController extends Controller
{
    /**
     * Obtener todas las reservas del usuario autenticado
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $reservations = Reservation::where('user_id', $user->id)
            ->with(['place', 'usuario:id,name,email,foto_perfil'])
            ->orderBy('fecha_visita', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($reservations);
    }

    /**
     * Obtener todas las reservas (solo para administradores en el futuro)
     * Por ahora, solo devuelve las del usuario autenticado
     */
    public function all(Request $request): JsonResponse
    {
        $user = $request->user();
        
        // Solo admin puede ver todas las reservas
        if (!$user->is_admin) {
            return response()->json(['message' => 'No autorizado'], 403);
        }
        
        $this->backfillCompanyReservations();

        $reservations = Reservation::with(['place', 'usuario', 'companyReservation.rejectionReason'])
            ->orderBy('fecha_visita', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($reservations);
    }

    private function backfillCompanyReservations(): void
    {
        $missingReservations = Reservation::whereDoesntHave('companyReservation')->get();

        if ($missingReservations->isEmpty()) {
            return;
        }

        $placeIds = $missingReservations->pluck('place_id')->unique()->values()->all();
        $places = Place::with('companyUsers')->whereIn('id', $placeIds)->get()->keyBy('id');

        foreach ($missingReservations as $reservation) {
            $place = $places->get($reservation->place_id);
            if (!$place) {
                continue;
            }

            $principalUser = $place->getPrincipalCompanyUser();
            if (!$principalUser) {
                $principalUser = $place->companyUsers()->first();
            }

            if (!$principalUser) {
                continue;
            }

            CompanyReservation::create([
                'reservation_id' => $reservation->id,
                'company_user_id' => $principalUser->id,
                'place_id' => $reservation->place_id,
                'estado' => 'pendiente',
            ]);
        }
    }

    /**
     * Crear una nueva reserva
     */
    public function store(Request $request): JsonResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'place_id' => 'required|exists:places,id',
            'fecha_visita' => 'required|date|after_or_equal:today',
            'hora_visita' => 'required|date_format:H:i',
            'personas' => 'required|integer|min:1|max:50',
            'telefono_contacto' => ['nullable', 'string', 'max:20', new NoProfanity()],
            'comentarios' => ['nullable', 'string', 'max:1000', new NoProfanity()],
            'precio_total' => 'nullable|numeric|min:0',
            'estado' => 'sometimes|string|in:pendiente,confirmada,cancelada',
        ], [
            'place_id.required' => 'El lugar es requerido.',
            'place_id.exists' => 'El lugar seleccionado no existe.',
            'fecha_visita.required' => 'La fecha de visita es requerida.',
            'fecha_visita.date' => 'La fecha de visita debe ser una fecha válida.',
            'fecha_visita.after_or_equal' => 'La fecha de visita debe ser hoy o una fecha futura.',
            'hora_visita.required' => 'La hora de visita es requerida.',
            'hora_visita.date_format' => 'La hora debe tener el formato HH:mm.',
            'personas.required' => 'El número de personas es requerido.',
            'personas.integer' => 'El número de personas debe ser un número entero.',
            'personas.min' => 'Debe haber al menos 1 persona.',
            'personas.max' => 'No puede haber más de 50 personas.',
            'telefono_contacto.max' => 'El teléfono no puede exceder 20 caracteres.',
            'comentarios.max' => 'Los comentarios no pueden exceder 1000 caracteres.',
            'precio_total.numeric' => 'El precio debe ser un número.',
            'precio_total.min' => 'El precio no puede ser negativo.',
        ]);

        // Obtener el lugar
        $place = \App\Models\Place::findOrFail($data['place_id']);

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
            return response()->json([
                'message' => 'El lugar está cerrado el día seleccionado.',
                'errors' => [
                    'fecha_visita' => ['El lugar está cerrado el ' . ucfirst($diaSemana) . '. Por favor, selecciona otro día.']
                ],
                'suggestions' => []
            ], 422);
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
            
            return response()->json([
                'message' => 'La hora seleccionada no está dentro del horario de atención del lugar.',
                'errors' => [
                    'hora_visita' => ['La hora seleccionada no está disponible. Horarios disponibles: ' . $horariosDisponibles]
                ],
                'suggestions' => []
            ], 422);
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
                    $suggestions[] = [
                        'hora' => $sugerenciaAntes->format('H:i'),
                        'descripcion' => '2 horas antes de la reserva existente'
                    ];
                }
                
                // Sugerencia 2 horas después
                if ($sugerenciaDespues->gte($horaInicioSchedule) && 
                    $sugerenciaDespues->copy()->addHours(2)->lte($horaFinSchedule)) {
                    $suggestions[] = [
                        'hora' => $sugerenciaDespues->format('H:i'),
                        'descripcion' => '2 horas después de la reserva existente'
                    ];
                }
            }

            $mensajeError = 'Ya existe una reserva que se solapa con el horario seleccionado. Cada reserva tiene una duración de 2 horas.';
            if (count($suggestions) > 0) {
                $mensajeError .= ' Horarios sugeridos: ' . implode(', ', array_map(function($s) {
                    return $s['hora'] . ' (' . $s['descripcion'] . ')';
                }, $suggestions));
            }

            return response()->json([
                'message' => $mensajeError,
                'errors' => [
                    'hora_visita' => [$mensajeError]
                ],
                'suggestions' => $suggestions,
                'conflicting_time' => $conflictingReservation->hora_visita
            ], 422);
        }

        $reservation = Reservation::create([
            'user_id' => $user->id,
            'place_id' => $data['place_id'],
            'fecha_reserva' => now(),
            'fecha_visita' => $data['fecha_visita'],
            'hora_visita' => $data['hora_visita'] ?? null,
            'fecha' => $data['fecha_visita'], // Mantener compatibilidad
            'personas' => $data['personas'],
            'telefono_contacto' => $data['telefono_contacto'] ?? null,
            'comentarios' => $data['comentarios'] ?? null,
            'precio_total' => $data['precio_total'] ?? null,
            'estado' => $data['estado'] ?? 'pendiente',
        ]);

        $reservation->load(['place', 'usuario']);

        return response()->json([
            'message' => 'Reserva creada correctamente.',
            'reservation' => $reservation
        ], 201);
    }

    /**
     * Obtener una reserva específica
     */
    public function show(Request $request, Reservation $reservation): JsonResponse
    {
        $user = $request->user();

        // Verificar que la reserva pertenece al usuario autenticado
        if ($reservation->user_id !== $user->id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $reservation->load(['place', 'usuario']);

        return response()->json($reservation);
    }

    /**
     * Actualizar una reserva
     */
    public function update(Request $request, Reservation $reservation): JsonResponse
    {
        $user = $request->user();

        // Verificar que la reserva pertenece al usuario autenticado O que el usuario es admin
        if ($reservation->user_id !== $user->id && !$user->is_admin) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $data = $request->validate([
            'fecha_visita' => 'sometimes|date',
            'hora_visita' => 'nullable|date_format:H:i',
            'personas' => 'sometimes|integer|min:1|max:50',
            'telefono_contacto' => ['nullable', 'string', 'max:20', new NoProfanity()],
            'comentarios' => ['nullable', 'string', 'max:1000', new NoProfanity()],
            'precio_total' => 'nullable|numeric|min:0',
            'estado' => 'sometimes|string|in:pendiente,confirmada,cancelada,completada,rechazada,aceptada',
        ], [
            'fecha_visita.date' => 'La fecha de visita debe ser una fecha válida.',
            'fecha_visita.after_or_equal' => 'La fecha de visita debe ser hoy o una fecha futura.',
            'hora_visita.date_format' => 'La hora debe tener el formato HH:mm.',
            'personas.integer' => 'El número de personas debe ser un número entero.',
            'personas.min' => 'Debe haber al menos 1 persona.',
            'personas.max' => 'No puede haber más de 50 personas.',
            'telefono_contacto.max' => 'El teléfono no puede exceder 20 caracteres.',
            'comentarios.max' => 'Los comentarios no pueden exceder 1000 caracteres.',
            'precio_total.numeric' => 'El precio debe ser un número.',
            'precio_total.min' => 'El precio no puede ser negativo.',
        ]);

        // Si se actualiza fecha_visita, también actualizar fecha para compatibilidad
        if (isset($data['fecha_visita'])) {
            $data['fecha'] = $data['fecha_visita'];
        }

        $reservation->update($data);
        $reservation->load(['place', 'usuario']);

        return response()->json($reservation);
    }

    /**
     * Eliminar una reserva
     */
    public function destroy(Request $request, Reservation $reservation): JsonResponse
    {
        $user = $request->user();

        // Verificar que la reserva pertenece al usuario autenticado O que el usuario es admin
        if ($reservation->user_id !== $user->id && !$user->is_admin) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $reservation->delete();

        return response()->json(['message' => 'Reserva eliminada correctamente'], 200);
    }

    /**
     * Obtener reservas del usuario autenticado
     */
    public function myReservations(Request $request): JsonResponse
    {
        $user = $request->user();
        $reservations = Reservation::where('user_id', $user->id)
            ->with(['place', 'usuario:id,name,email,foto_perfil'])
            ->orderBy('fecha_visita', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($reservations);
    }
}