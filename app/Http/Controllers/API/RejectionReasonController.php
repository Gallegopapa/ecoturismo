<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\RejectionReason;
use App\Rules\NoProfanity;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RejectionReasonController extends Controller
{
    /**
     * Obtener todas las razones de rechazo (público)
     */
    public function index(): JsonResponse
    {
        $reasons = RejectionReason::orderBy('code')->get();

        return response()->json($reasons);
    }

    /**
     * Crear una nueva razón de rechazo (solo admin)
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:rejection_reasons,code', new NoProfanity()],
            'descripcion' => ['required', 'string', 'max:255', new NoProfanity()],
            'detalles' => ['nullable', 'string', new NoProfanity()],
        ], [
            'code.required' => 'El código es requerido.',
            'code.unique' => 'Este código ya está registrado.',
            'descripcion.required' => 'La descripción es requerida.',
        ]);

        $reason = RejectionReason::create($data);

        return response()->json([
            'message' => 'Razón de rechazo creada correctamente.',
            'data' => $reason
        ], 201);
    }

    /**
     * Obtener una razón de rechazo específica (público)
     */
    public function show(RejectionReason $reason): JsonResponse
    {
        return response()->json($reason);
    }

    /**
     * Actualizar una razón de rechazo (solo admin)
     */
    public function update(Request $request, RejectionReason $reason): JsonResponse
    {
        $data = $request->validate([
            'code' => ['sometimes', 'required', 'string', 'max:50', 'unique:rejection_reasons,code,' . $reason->id, new NoProfanity()],
            'descripcion' => ['sometimes', 'required', 'string', 'max:255', new NoProfanity()],
            'detalles' => ['nullable', 'string', new NoProfanity()],
        ], [
            'code.unique' => 'Este código ya está registrado.',
            'descripcion.required' => 'La descripción es requerida.',
        ]);

        $reason->update($data);

        return response()->json([
            'message' => 'Razón de rechazo actualizada correctamente.',
            'data' => $reason
        ]);
    }

    /**
     * Eliminar una razón de rechazo (solo admin)
     */
    public function destroy(RejectionReason $reason): JsonResponse
    {
        // Verificar que no haya rechasos usando esta razón
        if ($reason->companyReservations()->exists()) {
            return response()->json([
                'message' => 'No se puede eliminar esta razón porque está siendo utilizada en rechasos.'
            ], 422);
        }

        $reason->delete();

        return response()->json([
            'message' => 'Razón de rechazo eliminada correctamente.'
        ]);
    }
}
