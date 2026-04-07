<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class PasswordResetController extends Controller
{
    public function sendResetLink(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'email' => 'required|email|max:255',
            ], [
                'email.required' => 'El correo electronico es requerido.',
                'email.email' => 'El correo electronico debe ser una direccion valida.',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors(),
            ], 422);
        }

        try {
            $status = Password::broker('users')->sendResetLink(
                $request->only('email')
            );
        } catch (\Symfony\Component\Mailer\Exception\TransportException $e) {
            \Log::error('SMTP error al enviar reset: ' . $e->getMessage());
            return response()->json([
                'message' => 'No se pudo conectar al servidor de correo. Por favor intenta más tarde.',
                'errors'  => ['email' => ['Error de conexión SMTP: ' . $e->getMessage()]],
            ], 503);
        } catch (\Exception $e) {
            \Log::error('Error al enviar reset: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error al enviar el correo. Por favor intenta más tarde.',
                'errors'  => ['email' => [$e->getMessage()]],
            ], 500);
        }

        if ($status === Password::RESET_LINK_SENT) {
            // AC1: Enlace enviado
            return response()->json([
                'message' => '¡Correo enviado! Revisa tu bandeja de entrada.',
            ], 200);
        }

        // AC2: Email not registered -> System returns appropriate response
        $mensajesError = [
            Password::RESET_THROTTLED => 'Espera unos segundos antes de solicitar otro enlace.',
            Password::INVALID_USER    => 'No encontramos ninguna cuenta con ese correo.',
        ];

        $mensaje = $mensajesError[$status]
            ?? 'No se pudo enviar el enlace. Intenta de nuevo más tarde.';

        return response()->json([
            'message' => $mensaje,
            'errors'  => [
                'email' => [$mensaje],
            ],
        ], 422);
    }
}
