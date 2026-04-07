<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Usuarios;
use Illuminate\Http\Request;

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
}
