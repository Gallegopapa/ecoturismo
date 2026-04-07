<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Obtener información del perfil del usuario autenticado
     */
    public function show(Request $request): JsonResponse
    {
        $user = $request->user();
        
        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'telefono' => $user->telefono,
                'foto_perfil' => $user->foto_perfil,
                'fecha_registro' => $user->fecha_registro,
                'is_admin' => $user->is_admin,
            ]
        ]);
    }

    /**
     * Actualizar información del perfil
     */
    public function update(Request $request): JsonResponse
    {
        $user = $request->user();

        $rules = [];
        $messages = [];

        // Solo aplicar validaciones estrictas de name si se envía y es diferente (AC1/AC2)
        $incomingName = $request->input('name');
        if ($incomingName !== null && !empty(trim((string) $incomingName))) {
            $normalizedIncomingName = trim((string) $incomingName);
            $currentName = trim((string) $user->name);

            // Si intenta cambiar el nombre, aplicar todas las reglas
            if (strcasecmp($normalizedIncomingName, $currentName) !== 0) {
                $rules['name'] = ['required', 'string', 'max:255', 'min:3', 'unique:usuarios,name,' . $user->id, 'regex:/^[a-zA-Z0-9_]+$/', new \App\Rules\NoProfanity()];
                $messages['name.required'] = 'El nombre de usuario es requerido.';
                $messages['name.min'] = 'El nombre de usuario debe tener al menos 3 caracteres.';
                $messages['name.unique'] = 'Este nombre de usuario ya está en uso.';
                $messages['name.regex'] = 'El nombre de usuario solo puede contener letras, números y guiones bajos.';
            }
        }

        // Correo electrónico (AC3/AC4)
        $rules['email'] = ['nullable', 'email', 'max:255', 'unique:usuarios,email,' . $user->id];
        $incomingEmail = $request->input('email');
        if ($incomingEmail !== null && strtolower(trim((string) $incomingEmail)) !== strtolower(trim((string) $user->email))) {
            $rules['email'][] = new \App\Rules\AllowedEmailDomain();
        }
        $messages['email.email'] = 'El correo electrónico debe ser válido.';
        $messages['email.unique'] = 'Este correo electrónico ya está en uso.';

        // Teléfono (AC5)
        $rules['telefono'] = ['nullable', 'string', 'max:20', new \App\Rules\NoProfanity()];
        $messages['telefono.max'] = 'El teléfono no puede exceder 20 caracteres.';

        $validated = $request->validate($rules, $messages);

        if (array_key_exists('name', $validated)) {
            $validated['name'] = trim((string) $validated['name']);
            if ($validated['name'] === '') {
                unset($validated['name']);
            }
        }

        if (array_key_exists('email', $validated) && $validated['email'] !== null) {
            $validated['email'] = strtolower(trim((string) $validated['email']));
        }

        $user->update($validated);
        $user->refresh();

        return response()->json([
            'message' => 'Perfil actualizado correctamente.',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'telefono' => $user->telefono,
                'foto_perfil' => $user->foto_perfil,
                'fecha_registro' => $user->fecha_registro,
                'is_admin' => $user->is_admin,
            ]
        ]);
    }
}
