<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\CompanyReservation;
use App\Models\RejectionReason;
use App\Rules\NoProfanity;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CompanyReservationController extends Controller
{
    /**
     * Obtener todas las reservas de los lugares que gestiona la empresa
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        // Validar que sea usuario empresa
        if (!$user->isCompanyUser()) {
            return response()->json([
                'message' => 'No tienes permisos para acceder a este recurso.'
            ], 403);
        }

        // Obtener lugares asignados al usuario
        $placesIds = $user->placesManaged()->pluck('places.id')->toArray();

        if (empty($placesIds)) {
            return response()->json([
                'message' => 'No tienes lugares asignados.'
            ], 403);
        }

        // Asegurar que las reservas existentes tengan registro en company_reservations
        $this->backfillCompanyReservations($placesIds);

        // Obtener parámetros de filtro
        $estado = $request->get('estado', 'pendiente');
        $placeId = $request->get('place_id');
        $fechaDesde = $request->get('fecha_desde');
        $fechaHasta = $request->get('fecha_hasta');

        // Construir query
        $query = CompanyReservation::whereIn('place_id', $placesIds)
            ->with('reservation.usuario', 'reservation.place', 'rejectionReason');

        // Filtrar por estado
        if ($estado) {
            $query->where('estado', $estado);
        }

        // Filtrar por lugar
        if ($placeId && in_array($placeId, $placesIds)) {
            $query->where('place_id', $placeId);
        }

        // Filtrar por fecha
        if ($fechaDesde) {
            $query->whereDate('created_at', '>=', $fechaDesde);
        }

        if ($fechaHasta) {
            $query->whereDate('created_at', '<=', $fechaHasta);
        }

        $reservations = $query->orderBy('created_at', 'desc')->paginate(15);

        return response()->json([
            'data' => $reservations->items(),
            'pagination' => [
                'current_page' => $reservations->currentPage(),
                'last_page' => $reservations->lastPage(),
                'per_page' => $reservations->perPage(),
                'total' => $reservations->total(),
            ]
        ]);
    }

    private function backfillCompanyReservations(array $placesIds): void
    {
        if (empty($placesIds)) {
            return;
        }

        $places = \App\Models\Place::with('companyUsers')
            ->whereIn('id', $placesIds)
            ->get()
            ->keyBy('id');

        $missingReservations = Reservation::whereIn('place_id', $placesIds)
            ->whereDoesntHave('companyReservation')
            ->get();

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
     * Obtener detalles de una reserva específica
     */
    public function show(Request $request, CompanyReservation $companyReservation): JsonResponse
    {
        $user = $request->user();

        // Validar permisos
        if ($companyReservation->company_user_id !== $user->id && 
            !in_array($companyReservation->place_id, $user->placesManaged()->pluck('places.id')->toArray())) {
            return response()->json([
                'message' => 'No tienes permisos para ver esta reserva.'
            ], 403);
        }

        return response()->json($companyReservation->load('reservation.usuario', 'reservation.place', 'rejectionReason'));
    }

    /**
     * Aceptar una reserva
     */
    public function accept(Request $request, CompanyReservation $companyReservation): JsonResponse
    {
        $user = $request->user();

        // Validar permisos
        if (!in_array($companyReservation->place_id, $user->placesManaged()->pluck('places.id')->toArray())) {
            return response()->json([
                'message' => 'No tienes permisos para aceptar esta reserva.'
            ], 403);
        }

        // Validar que esté pendiente
        if (!$companyReservation->isPending()) {
            return response()->json([
                'message' => 'Esta reserva ya tiene una respuesta.'
            ], 422);
        }

        // Aceptar la reserva
        $companyReservation->accept();
        
        // Actualizar el estado de la reserva principal
        $companyReservation->reservation->update(['estado' => 'aceptada']);

        // TODO: Enviar email de confirmación al cliente

        return response()->json([
            'message' => 'Reserva aceptada correctamente.',
            'data' => $companyReservation->load('reservation.usuario', 'reservation.place', 'rejectionReason')
        ]);
    }

    /**
     * Rechazar una reserva
     */
    public function reject(Request $request, CompanyReservation $companyReservation): JsonResponse
    {
        $user = $request->user();

        // Validar permisos
        if (!in_array($companyReservation->place_id, $user->placesManaged()->pluck('places.id')->toArray())) {
            return response()->json([
                'message' => 'No tienes permisos para rechazar esta reserva.'
            ], 403);
        }

        // Validar que esté pendiente
        if (!$companyReservation->isPending()) {
            return response()->json([
                'message' => 'Esta reserva ya tiene una respuesta.'
            ], 422);
        }

        // Validar datos de rechazo
        $data = $request->validate([
            'rejection_reason_id' => 'required|exists:rejection_reasons,id',
            'comentario' => ['nullable', 'string', 'max:500', new NoProfanity()],
        ], [
            'rejection_reason_id.required' => 'La razón de rechazo es requerida.',
            'rejection_reason_id.exists' => 'La razón de rechazo seleccionada no existe.',
            'comentario.max' => 'El comentario no puede exceder 500 caracteres.',
        ]);

        // Rechazar la reserva
        $companyReservation->reject(
            $data['rejection_reason_id'],
            $data['comentario'] ?? null
        );

        // Actualizar el estado de la reserva principal
        $companyReservation->reservation->update(['estado' => 'rechazada']);

        // TODO: Enviar email de rechazo al cliente con el motivo

        return response()->json([
            'message' => 'Reserva rechazada correctamente.',
            'data' => $companyReservation->load('reservation.usuario', 'reservation.place', 'rejectionReason')
        ]);
    }

    /**
     * Reabrir una reserva rechazada
     */
    public function reopen(Request $request, CompanyReservation $companyReservation): JsonResponse
    {
        $user = $request->user();

        // Validar permisos
        if (!in_array($companyReservation->place_id, $user->placesManaged()->pluck('places.id')->toArray())) {
            return response()->json([
                'message' => 'No tienes permisos para reabrir esta reserva.'
            ], 403);
        }

        // Solo se puede reabrir si esta rechazada o aceptada
        if (!$companyReservation->isRejected() && !$companyReservation->isAccepted()) {
            return response()->json([
                'message' => 'Solo se pueden reabrir reservas rechazadas o aceptadas.'
            ], 422);
        }

        $companyReservation->reopen();
        $companyReservation->reservation->update(['estado' => 'pendiente']);

        return response()->json([
            'message' => 'Reserva reabierta correctamente.',
            'data' => $companyReservation->load('reservation.usuario', 'reservation.place', 'rejectionReason')
        ]);
    }

    /**
     * Obtener estadísticas de reservas para un lugar
     */
    public function stats(Request $request, int $placeId): JsonResponse
    {
        $user = $request->user();

        // Validar que la empresa gestiona este lugar
        if (!$user->placesManaged()->where('place_id', $placeId)->exists()) {
            return response()->json([
                'message' => 'No tienes permisos para ver estas estadísticas.'
            ], 403);
        }

        $stats = [
            'pendientes' => CompanyReservation::where('place_id', $placeId)
                ->where('estado', 'pendiente')
                ->count(),
            'aceptadas' => CompanyReservation::where('place_id', $placeId)
                ->where('estado', 'aceptada')
                ->count(),
            'rechazadas' => CompanyReservation::where('place_id', $placeId)
                ->where('estado', 'rechazada')
                ->count(),
            'total' => CompanyReservation::where('place_id', $placeId)->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Obtener estadísticas globales de reservas para la empresa
     */
    public function statsSummary(Request $request): JsonResponse
    {
        $user = $request->user();

        // Validar que sea usuario empresa
        if (!$user->isCompanyUser()) {
            return response()->json([
                'message' => 'No tienes permisos para acceder a este recurso.'
            ], 403);
        }

        // Obtener lugares asignados al usuario
        $placesIds = $user->placesManaged()->pluck('places.id')->toArray();

        if (empty($placesIds)) {
            return response()->json([
                'message' => 'No tienes lugares asignados.'
            ], 403);
        }

        $stats = [
            'pendientes' => CompanyReservation::whereIn('place_id', $placesIds)
                ->where('estado', 'pendiente')
                ->count(),
            'aceptadas' => CompanyReservation::whereIn('place_id', $placesIds)
                ->where('estado', 'aceptada')
                ->count(),
            'rechazadas' => CompanyReservation::whereIn('place_id', $placesIds)
                ->where('estado', 'rechazada')
                ->count(),
            'total' => CompanyReservation::whereIn('place_id', $placesIds)->count(),
        ];

        return response()->json($stats);
    }
}
