<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Usuarios;
use App\Rules\AllowedEmailDomain;
use App\Rules\NoProfanity;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    /**
     * Obtener todos los usuarios
     */
    public function index(): JsonResponse
    {
        $users = Usuarios::orderBy('id', 'desc')->get();
        
        return response()->json($users->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'telefono' => $user->telefono,
                'is_admin' => $user->is_admin,
                'tipo_usuario' => $user->tipo_usuario,
                'fecha_registro' => $user->fecha_registro,
                'lugares_asignados' => $user->tipo_usuario === 'empresa' 
                    ? $user->placesManaged()->count() 
                    : null,
            ];
        }));
    }

    /**
     * Obtener un usuario específico con sus lugares asignados
     */
    public function show(Usuarios $user): JsonResponse
    {
        $response = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'telefono' => $user->telefono,
            'is_admin' => $user->is_admin,
            'tipo_usuario' => $user->tipo_usuario,
            'fecha_registro' => $user->fecha_registro,
        ];

        // Si es usuario empresa, incluir lugares asignados
        if ($user->tipo_usuario === 'empresa') {
            $response['lugares_asignados'] = $user->placesManaged()->map(function ($place) {
                return [
                    'id' => $place->id,
                    'name' => $place->name,
                    'es_principal' => $place->pivot->es_principal,
                ];
            });
        }

        return response()->json($response);
    }

    /**
     * Crear un nuevo usuario
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'min:3', 'unique:usuarios,name', 'regex:/^[a-zA-Z0-9_]+$/', new NoProfanity()],
            'email' => ['nullable', 'email', 'max:255', new AllowedEmailDomain(), 'unique:usuarios,email'],
            'password' => 'nullable|string|min:8|max:15',
            'is_admin' => 'nullable|boolean',
            'tipo_usuario' => 'required|in:normal,empresa,admin',
            'lugares' => 'nullable|array',
            'lugares.*.place_id' => 'required_with:lugares|integer|exists:places,id',
            'lugares.*.es_principal' => 'nullable|boolean',
        ], [
            'name.required' => 'El nombre de usuario es requerido.',
            'name.unique' => 'Este nombre de usuario ya está en uso.',
            'name.regex' => 'El nombre de usuario solo puede contener letras, números y guiones bajos.',
            'email.email' => 'El email debe ser válido.',
            'email.unique' => 'Este email ya está registrado.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.max' => 'La contraseña no puede tener más de 15 caracteres.',
            'tipo_usuario.required' => 'El tipo de usuario es requerido.',
            'tipo_usuario.in' => 'El tipo de usuario debe ser: normal, empresa o admin.',
            'lugares.*.place_id.exists' => 'Uno de los lugares seleccionados no existe.',
        ]);

        // Generar contraseña aleatoria segura si no se proporciona
        $plainPassword = $data['password'] ?? null;
        if (!$plainPassword) {
            $plainPassword = $this->generateSecurePassword();
        }

        $userData = [
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'password' => Hash::make($plainPassword),
            'is_admin' => $data['tipo_usuario'] === 'admin',
            'tipo_usuario' => $data['tipo_usuario'],
            'fecha_registro' => now(),
        ];

        $user = Usuarios::create($userData);

        // Si es usuario empresa y se proporcionan lugares, asignarlos
        if ($data['tipo_usuario'] === 'empresa' && isset($data['lugares'])) {
            $this->assignPlacesToUser($user, $data['lugares']);
        }

        $response = [
            'message' => 'Usuario creado correctamente.',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'is_admin' => $user->is_admin,
                'tipo_usuario' => $user->tipo_usuario,
            ]
        ];

        // Solo devolver la contraseña si fue generada automáticamente
        if (!isset($data['password'])) {
            $response['generated_password'] = $plainPassword;
            $response['message'] = 'Usuario creado correctamente. La contraseña generada se muestra a continuación.';
        }

        return response()->json($response, 201);
    }

    /**
     * Generar una contraseña aleatoria segura
     */
    private function generateSecurePassword(int $length = 12): string
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
        $password = '';
        $max = strlen($characters) - 1;
        
        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[random_int(0, $max)];
        }
        
        return $password;
    }

    /**
     * Actualizar un usuario
     */
    public function update(Request $request, Usuarios $user): JsonResponse
    {
        $data = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255', 'min:3', 'unique:usuarios,name,' . $user->id, 'regex:/^[a-zA-Z0-9_]+$/', new NoProfanity()],
            'email' => ['nullable', 'email', 'max:255', new AllowedEmailDomain(), 'unique:usuarios,email,' . $user->id],
            'password' => 'nullable|string|min:8|max:15',
            'is_admin' => 'nullable|boolean',
            'tipo_usuario' => 'sometimes|required|in:normal,empresa,admin',
            'lugares' => 'nullable|array',
            'lugares.*.place_id' => 'required_with:lugares|integer|exists:places,id',
            'lugares.*.es_principal' => 'nullable|boolean',
        ], [
            'name.required' => 'El nombre de usuario es requerido.',
            'name.unique' => 'Este nombre de usuario ya está en uso.',
            'name.regex' => 'El nombre de usuario solo puede contener letras, números y guiones bajos.',
            'email.email' => 'El email debe ser válido.',
            'email.unique' => 'Este email ya está registrado.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.max' => 'La contraseña no puede tener más de 15 caracteres.',
            'tipo_usuario.in' => 'El tipo de usuario debe ser: normal, empresa o admin.',
        ]);

        $currentUser = $request->user();
        if ($currentUser && $user->id === $currentUser->id) {
            if ((isset($data['tipo_usuario']) && $data['tipo_usuario'] !== 'admin') ||
                (isset($data['is_admin']) && $data['is_admin'] === false)
            ) {
                return response()->json([
                    'errors' => [
                        'tipo_usuario' => ['No puedes remover tus propios permisos de administrador o cambiar tu tipo de usuario.']
                    ]
                ], 422);
            }
        }

        $updateData = [];
        
        if (isset($data['name'])) {
            $updateData['name'] = $data['name'];
        }
        
        if (isset($data['email'])) {
            $updateData['email'] = $data['email'];
        }
        
        if (isset($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }
        
        if (isset($data['tipo_usuario'])) {
            $updateData['tipo_usuario'] = $data['tipo_usuario'];
            // Si se cambia a admin, marcar como admin también
            if ($data['tipo_usuario'] === 'admin') {
                $updateData['is_admin'] = true;
            } else {
                $updateData['is_admin'] = false;
            }
        } elseif (isset($data['is_admin'])) {
            $updateData['is_admin'] = $data['is_admin'];
        }

        $user->update($updateData);

        // Si se proporcionan lugares, actualizar asignaciones
        if (isset($data['lugares'])) {
            $user->placesManaged()->detach();
            if ($data['tipo_usuario'] === 'empresa' || $user->tipo_usuario === 'empresa') {
                $this->assignPlacesToUser($user, $data['lugares']);
            }
        }

        return response()->json([
            'message' => 'Usuario actualizado correctamente.',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'is_admin' => $user->is_admin,
                'tipo_usuario' => $user->tipo_usuario,
            ]
        ]);
    }

    /**
     * Eliminar un usuario
     */
    public function destroy(Request $request, Usuarios $user): JsonResponse
    {
        // No permitir eliminar al usuario actual
        $currentUser = $request->user();
        if ($currentUser && $user->id === $currentUser->id) {
            return response()->json([
                'message' => 'No puedes eliminar tu propio usuario.'
            ], 422);
        }

        $user->delete();

        return response()->json([
            'message' => 'Usuario eliminado correctamente.'
        ]);
    }

    /**
     * Asignar lugares a un usuario empresa
     */
    private function assignPlacesToUser(Usuarios $user, array $lugares): void
    {
        $placeAssignments = [];
        
        foreach ($lugares as $lugar) {
            $placeAssignments[$lugar['place_id']] = [
                'es_principal' => $lugar['es_principal'] ?? false,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $user->placesManaged()->sync($placeAssignments);
    }
}
