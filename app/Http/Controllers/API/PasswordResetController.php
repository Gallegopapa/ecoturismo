<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
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
                'message' => 'Error de validacion',
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
            return response()->json([
                'message' => '¡Correo enviado! Revisa tu bandeja de entrada.',
            ], 200);
        }

        // Mensajes de error en español por tipo de fallo
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


    public function resetPassword(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'token' => 'required|string',
                'email' => 'required|email|max:255',
                'password' => 'required|string|min:6|max:20|confirmed',
            ], [
                'token.required' => 'El token es requerido.',
                'email.required' => 'El correo electronico es requerido.',
                'email.email' => 'El correo electronico debe ser una direccion valida.',
                'password.required' => 'La contrasena es requerida.',
                'password.min' => 'La contrasena debe tener al menos 6 caracteres.',
                'password.max' => 'La contrasena no puede tener mas de 20 caracteres.',
                'password.confirmed' => 'Las contrasenas no coinciden.',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Error de validacion',
                'errors' => $e->errors(),
            ], 422);
        }

        $status = Password::broker('users')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = bcrypt($password);
                $user->setRememberToken(Str::random(60));
                $user->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'message' => 'Contrasena restablecida correctamente.',
            ], 200);
        }

        return response()->json([
            'message' => 'No se pudo restablecer la contrasena.',
            'errors' => [
                'email' => [trans($status)],
            ],
        ], 422);
    }
}
