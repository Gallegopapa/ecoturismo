<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Usuarios;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use App\Rules\AllowedEmailDomain;

class AuthController extends Controller
{

    /**
     * REGISTRO DE USUARIO
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [

            'name' => [
                'required',
                'string',
                'min:3',
                'max:255',
                'regex:/^[a-zA-Z0-9_]+$/',
                Rule::unique('usuarios', 'name')
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                new AllowedEmailDomain(),
                Rule::unique('usuarios', 'email')
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'max:15',
                'confirmed'
            ]

        ], [

            'name.required' => 'El nombre de usuario es requerido.',
            'name.min' => 'El nombre debe tener mínimo 3 caracteres.',
            'name.unique' => 'Este nombre ya está en uso.',
            'name.regex' => 'Solo se permiten letras, números y guiones bajos.',

            'email.required' => 'El correo es obligatorio.',
            'email.email' => 'El correo debe ser válido.',
            'email.unique' => 'Este correo ya está registrado.',

            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.max' => 'La contraseña no puede tener más de 15 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.'

        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();

        try {
            [$user, $token] = DB::transaction(function () use ($data) {
                $normalizedName = trim($data['name']);
                $normalizedEmail = strtolower(trim($data['email']));

                $user = Usuarios::create([
                    'name' => $normalizedName,
                    'email' => $normalizedEmail,
                    'password' => Hash::make($data['password']),
                    'fecha_registro' => now(),
                ]);

                // En algunos entornos el ID puede no venir inmediatamente en la instancia creada.
                $user = $user->fresh() ?? Usuarios::query()->where('email', $normalizedEmail)->first();

                if (!$user || !$user->id) {
                    throw ValidationException::withMessages([
                        'register' => ['No se pudo confirmar el ID del usuario creado.'],
                    ]);
                }

                $token = $user->createToken('api-token')->plainTextToken;

                return [$user, $token];
            });
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Error al registrar usuario',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            Log::error('Fallo en registro de usuario', [
                'message' => $e->getMessage(),
                'db_connection' => config('database.default'),
                'exception' => get_class($e),
            ]);

            return response()->json([
                'message' => 'No fue posible registrar el usuario en este momento.',
            ], 500);
        }

        return response()->json([

            'message' => 'Usuario registrado exitosamente',

            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'telefono' => $user->telefono,
                'foto_perfil' => $user->foto_perfil,
                'fecha_registro' => $user->fecha_registro,
                'is_admin' => $user->is_admin,
                'tipo_usuario' => $user->tipo_usuario
            ],

            'token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => 60 * 60 * 24 * 30

        ], 201);
    }

    /**
     * LOGIN
     */
    public function login(Request $request): JsonResponse
    {

        $validator = Validator::make($request->all(), [

            'login' => 'nullable|string|max:255',
            'email' => 'nullable|string|max:255',
            'username' => 'nullable|string|max:255',
            'password' => 'required|string'

        ], [

            'password.required' => 'La contraseña es requerida.'

        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        $rawLogin = $request->input('login')
            ?? $request->input('email')
            ?? $request->input('username');

        $login = strtolower(trim((string) $rawLogin));

        if ($login === '') {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => [
                    'login' => ['El correo o usuario es requerido.']
                ]
            ], 422);
        }

        $password = $request->password;

        $user = Usuarios::whereRaw('LOWER(email) = ?', [$login])
            ->orWhereRaw('LOWER(name) = ?', [$login])
            ->first();

        if (!$user) {

            Log::warning('Login fallido - usuario no encontrado', [
                'login' => $login
            ]);

            return response()->json([
                'message' => 'Credenciales incorrectas'
            ], 401);
        }

        if (!Hash::check($password, $user->password)) {

            Log::warning('Login fallido - contraseña incorrecta', [
                'user_id' => $user->id
            ]);

            return response()->json([
                'message' => 'Credenciales incorrectas'
            ], 401);
        }

        // eliminar tokens anteriores
        $user->tokens()->delete();

        $token = $user->createToken('api-token')->plainTextToken;

        Log::info('Login exitoso', [
            'user_id' => $user->id
        ]);

        return response()->json([

            'message' => 'Inicio de sesión exitoso',

            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'telefono' => $user->telefono,
                'foto_perfil' => $user->foto_perfil,
                'fecha_registro' => $user->fecha_registro,
                'is_admin' => $user->is_admin,
                'tipo_usuario' => $user->tipo_usuario
            ],

            'token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => 60 * 60 * 24 * 30

        ], 200);
    }

    /**
     * LOGOUT
     */
    public function logout(Request $request): JsonResponse
    {

        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada correctamente'
        ]);
    }


    /**
     * LOGOUT TODOS
     */
    public function logoutAll(Request $request): JsonResponse
    {

        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Todas las sesiones cerradas'
        ]);
    }


    /**
     * USUARIO ACTUAL
     */
    public function me(Request $request): JsonResponse
    {

        $user = $request->user();
        $user->load('reservations');

        return response()->json([

            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'telefono' => $user->telefono,
                'foto_perfil' => $user->foto_perfil,
                'fecha_registro' => $user->fecha_registro,
                'is_admin' => $user->is_admin,
                'tipo_usuario' => $user->tipo_usuario,
                'reservations_count' => $user->reservations->count()
            ],

            'reservations' => $user->reservations

        ]);
    }


    /**
     * VERIFICAR TOKEN
     */
    public function verifyToken(Request $request): JsonResponse
    {

        $user = $request->user();

        if (!$user) {
            return response()->json([
                'valid' => false,
                'message' => 'Token inválido'
            ], 401);
        }

        return response()->json([

            'valid' => true,

            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'telefono' => $user->telefono,
                'foto_perfil' => $user->foto_perfil,
                'is_admin' => $user->is_admin,
                'tipo_usuario' => $user->tipo_usuario
            ],

            'message' => 'Token válido'

        ]);
    }

}