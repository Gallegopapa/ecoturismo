<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PaymentController extends Controller
{
    /**
     * Crear un pago (BOCETO - No funcional completo)
     * Este es un ejemplo de cómo se implementaría el sistema de pagos
     */
    public function store(Request $request): JsonResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'reservation_id' => 'required|exists:reservations,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'nullable|string|in:stripe,paypal,transferencia,efectivo',
        ]);

        // Verificar que la reserva pertenece al usuario
        $reservation = Reservation::findOrFail($data['reservation_id']);
        if ($reservation->user_id !== $user->id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        // BOCETO: Aquí iría la integración con la pasarela de pagos
        // Por ahora solo creamos el registro del pago como "pending"
        
        $payment = Payment::create([
            'reservation_id' => $data['reservation_id'],
            'user_id' => $user->id,
            'amount' => $data['amount'],
            'currency' => 'COP',
            'payment_method' => $data['payment_method'] ?? 'transferencia',
            'status' => 'pending',
            'transaction_id' => 'BOCETO-' . uniqid(), // En producción sería el ID real de la transacción
        ]);

        return response()->json([
            'message' => 'Pago creado (BOCETO - No procesado)',
            'payment' => $payment,
            'note' => 'Este es un boceto. La integración real con pasarela de pagos debe implementarse aquí.'
        ], 201);
    }

    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $payments = Payment::where('user_id', $user->id)
            ->with('reservation.place')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($payments);
    }
}
