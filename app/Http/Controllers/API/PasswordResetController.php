<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordResetController extends Controller
{
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = Usuarios::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Email no encontrado'
            ], 404);
        }

        // AC1: Correct email -> Returns 200 + simulated message
        return response()->json([
            'message' => 'Se ha enviado un enlace a tu bandeja de entrada'
        ], 200);
    }

    public function resetPassword(Request $request)
    {
        // AC6: Password reset link simulation (requires token, email, matching passwords)
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8|max:15',
            'token' => 'required'
        ]);

        $user = Usuarios::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Email no encontrado'
            ], 404);
        }

        // AC5: New password update -> Returns 200
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'message' => 'Contraseña restablecida exitosamente'
        ], 200);
    }
}
