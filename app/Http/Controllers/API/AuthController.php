<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'min:3',
                'unique:usuarios,name',
                'regex:/^[a-zA-Z0-9]+$/'
            ],
            'email' => [
                'required',
                'unique:usuarios,email',
                'regex:/^.+@gmail\.com$/'
            ],
            'password' => [
                'required',
                'min:8',
                'max:15',
                'confirmed'
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        // AC1: Data is valid -> Returns 201 + token + user data
        $user = Usuarios::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'tipo_usuario' => 'registrado', // Default role for visitors
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ], 201);
    }

    public function login(Request $request)
    {
        // AC6 & AC7: Empty fields (Returns 422)
        $validator = Validator::make($request->all(), [
            'login' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }
    }
}
