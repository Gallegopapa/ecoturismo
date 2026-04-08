<?php

namespace App\Observers;

use App\Models\Reservation;
use App\Models\CompanyReservation;

class ReservationObserver
{
    /**
     * Handle the Reservation "created" event.
     */
    public function created(Reservation $reservation): void
    {
        // Obtener el usuario principal del lugar (o un fallback si no hay principal)
        $principalUser = $reservation->place->getPrincipalCompanyUser();

        if (!$principalUser) {
            $principalUser = $reservation->place->companyUsers()->first();
        }

        // Si el lugar tiene usuario empresa asignado, crear el registro en company_reservations
        if ($principalUser) {
            CompanyReservation::create([
                'reservation_id' => $reservation->id,
                'company_user_id' => $principalUser->id,
                'place_id' => $reservation->place_id,
                'estado' => 'pendiente',
            ]);
        }
    }

    /**
     * Handle the Reservation "updated" event.
     */
    public function updated(Reservation $reservation): void
    {
        // Si el estado cambió a aceptada/rechazada, actualizar company_reservation
        if ($reservation->isDirty('estado')) {
            $companyReservation = $reservation->companyReservation;
            
            if ($companyReservation) {
                // No hacer nada si ya fue respondida
                if ($companyReservation->isPending()) {
                    // Auto-aceptar si la reserva se marca como aceptada
                    if ($reservation->estado === 'aceptada') {
                        $companyReservation->accept();
                    }
                }
            }
        }
    }

    /**
     * Handle the Reservation "deleted" event.
     */
    public function deleted(Reservation $reservation): void
    {
        // Eliminar el registro de company_reservation asociado
        $reservation->companyReservation?->delete();
    }
}
