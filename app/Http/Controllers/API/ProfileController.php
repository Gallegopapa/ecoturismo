<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Rules\NoProfanity;
use App\Rules\AllowedEmailDomain;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Servir foto vía Query Parameter (Evasión 404 estático de Proxies).
     */
    public function photoByQuery(Request $request)
    {
        $filename = $request->query('f');
        if (!$filename) {
            abort(404);
        }
        return $this->photo($filename);
    }

    /**
     * Servir foto de perfil desde rutas conocidas (public y storage).
     */
    public function photo(string $filename)
    {
        $safeFilename = basename($filename);
        if ($safeFilename === '') {
            abort(404);
        }

        $candidates = [
            public_path('imagenes/' . $safeFilename),
            public_path('imagenes/perfiles/' . $safeFilename),
            storage_path('app/public/profiles/' . $safeFilename),
            storage_path('app/public/' . $safeFilename),
            storage_path('app/profiles/' . $safeFilename),
            storage_path('app/' . $safeFilename),
        ];

        foreach ($candidates as $path) {
            if (file_exists($path) && filesize($path) > 0) {
                $mimeType = 'image/jpeg';
                $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                if ($ext === 'png') $mimeType = 'image/png';
                elseif ($ext === 'gif') $mimeType = 'image/gif';
                elseif ($ext === 'webp') $mimeType = 'image/webp';
                
                return response()->file($path, [
                    'Content-Type' => $mimeType,
                    'Cache-Control' => 'public, max-age=86400'
                ]);
            }
        }

        abort(404);
    }

    /**
     * Eliminar la cuenta del usuario autenticado
     */
    public function destroy(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->delete();
        return response()->json([
            'message' => 'Cuenta eliminada exitosamente.'
        ]);
    }
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

        // Log detallado para debugging
        Log::info('Profile update request', [
            'method' => $request->method(),
            'has_file' => $request->hasFile('foto_perfil'),
            'inputs' => array_keys($request->all()),
            'has_base64' => $request->has('foto_perfil_base64'),
            'base64_length' => $request->has('foto_perfil_base64') ? strlen($request->input('foto_perfil_base64')) : 0,
        ]);

        if ($request->hasFile('foto_perfil')) {
            $file = $request->file('foto_perfil');
            Log::info('Foto recibida y válida', [
                'name' => $file->getClientOriginalName(),
                'mime' => $file->getMimeType(),
                'size' => $file->getSize(),
            ]);
        } else {
            // Check if there is a file object that failed validation (e.g., upload_max_filesize limit)
            $fileObj = $request->file('foto_perfil');
            if ($fileObj !== null && !$fileObj->isValid()) {
                $errorMsg = $fileObj->getErrorMessage();
                Log::error('Archivo de foto de perfil invalido', [
                    'error' => $errorMsg,
                    'error_code' => $fileObj->getError(),
                ]);
                return response()->json([
                    'message' => "La imagen no se pudo subir. Probablemente exceda el límite de tamaño del servidor (upload_max_filesize en Docker). Detalle: {$errorMsg}"
                ], 422);
            }
        }

        $emailRules = ['nullable', 'email', 'max:255', 'unique:usuarios,email,' . $user->id];
        $nameRules = ['nullable', 'string', 'max:255'];

        // Solo aplicar validaciones estrictas de name si se envía y es diferente
        $incomingName = $request->input('name');
        if ($incomingName !== null && !empty(trim((string) $incomingName))) {
            $normalizedIncomingName = trim((string) $incomingName);
            $currentName = trim((string) $user->name);

            // Si intenta cambiar el nombre, aplicar todas las reglas
            if (strcasecmp($normalizedIncomingName, $currentName) !== 0) {
                $nameRules = ['required', 'string', 'max:255', 'min:3', 'unique:usuarios,name,' . $user->id, 'regex:/^[a-zA-Z0-9_]+$/', new NoProfanity()];
            }
        }

        // Solo exigir dominio @gmail.com si el usuario intenta cambiar su correo.
        $incomingEmail = $request->input('email');
        if ($incomingEmail !== null && strtolower(trim((string) $incomingEmail)) !== strtolower(trim((string) $user->email))) {
            $emailRules[] = new AllowedEmailDomain();
        }

        $rules = [
            'name' => $nameRules,
            'email' => $emailRules,
            'telefono' => ['nullable', 'string', 'max:20', new NoProfanity()],
            'foto_perfil' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'], // 5MB maximo
            'foto_perfil_base64' => ['nullable', 'string'],
        ];

        $validated = $request->validate($rules, [
            'name.required' => 'El nombre de usuario es requerido.',
            'name.min' => 'El nombre de usuario debe tener al menos 3 caracteres.',
            'name.unique' => 'Este nombre de usuario ya está en uso.',
            'name.regex' => 'El nombre de usuario solo puede contener letras, números y guiones bajos.',
            'email.email' => 'El correo electrónico debe ser válido.',
            'email.unique' => 'Este correo electrónico ya está en uso.',
            'telefono.max' => 'El teléfono no puede exceder 20 caracteres.',
            'foto_perfil.image' => 'El archivo debe ser una imagen.',
            'foto_perfil.max' => 'La imagen no puede exceder 5MB.',
        ]);

        if (array_key_exists('name', $validated)) {
            $validated['name'] = trim((string) $validated['name']);
            if ($validated['name'] === '') {
                unset($validated['name']);
            }
        }

        if (array_key_exists('email', $validated) && $validated['email'] !== null) {
            $validated['email'] = strtolower(trim((string) $validated['email']));
        }

        // Manejar subida de foto de perfil
        $hasBase64 = $request->has('foto_perfil_base64') && strpos($request->input('foto_perfil_base64'), 'data:image') === 0;

        if ($request->hasFile('foto_perfil') || $hasBase64) {
            try {
                // Eliminar foto anterior si existe
                if ($user->foto_perfil) {
                    $oldImagePath = parse_url((string) $user->foto_perfil, PHP_URL_PATH) ?: (string) $user->foto_perfil;
                    $oldFileName = basename((string) $oldImagePath);

                    if ($oldFileName) {
                        try {
                            if (Storage::disk('public')->exists('profiles/' . $oldFileName)) {
                                Storage::disk('public')->delete('profiles/' . $oldFileName);
                            }
                            if (Storage::disk('public')->exists($oldFileName)) {
                                Storage::disk('public')->delete($oldFileName);
                            }
                            $oldPublicImagePath = public_path('imagenes/perfiles/' . $oldFileName);
                            if (File::exists($oldPublicImagePath)) {
                                File::delete($oldPublicImagePath);
                            }
                            $oldPublicFlatImagePath = public_path('imagenes/' . $oldFileName);
                            if (File::exists($oldPublicFlatImagePath)) {
                                File::delete($oldPublicFlatImagePath);
                            }
                            Log::info('Foto antigua eliminada', ['filename' => $oldFileName]);
                        } catch (\Exception $delEx) {
                            Log::warning('No se pudo borrar foto antigua', ['err' => $delEx->getMessage()]);
                        }
                    }
                }

                // Subir nueva foto
                $storedPath = null;
                $storageErrors = [];

                if ($hasBase64) {
                    $base64data = $request->input('foto_perfil_base64');
                    list($type, $base64data) = explode(';', $base64data);
                    list(, $base64data)      = explode(',', $base64data);
                    $fileData = base64_decode($base64data);
                    
                    $extension = 'jpg';
                    if (preg_match('/^data:image\/(\w+)/', $request->input('foto_perfil_base64'), $matches)) {
                        $extension = $matches[1] === 'jpeg' ? 'jpg' : $matches[1];
                    }
                    $filename = time() . '_' . uniqid() . '.' . $extension;
                } else {
                    $image = $request->file('foto_perfil');
                    $fileData = file_get_contents($image->getRealPath());
                    $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                }

                Log::info('Intentando guardar foto', [
                    'filename' => $filename,
                    'destination' => 'storage/app/public/profiles/' . $filename,
                ]);

                // Candidatos en orden de preferencia, asegurando permisos laxos para que funcione en Docker
                $storageCandidates = [
                    public_path('imagenes/perfiles')    => public_path('imagenes/perfiles/' . $filename),
                    public_path('imagenes')             => public_path('imagenes/' . $filename),
                    storage_path('app/public/profiles') => storage_path('app/public/profiles/' . $filename),
                    storage_path('app/profiles')        => storage_path('app/profiles/' . $filename),
                ];

                foreach ($storageCandidates as $dir => $targetPath) {
                    if (!is_dir($dir)) {
                        @mkdir($dir, 0777, true);
                    }
                    if (!is_dir($dir) || !is_writable($dir)) {
                        $storageErrors[] = $dir . ': no existe o sin permisos de escritura';
                        continue;
                    }
                    try {
                        if (file_put_contents($targetPath, $fileData) !== false) {
                            @chmod($targetPath, 0666);
                            $storedPath = $targetPath;
                            break;
                        }
                    } catch (\Throwable $moveErr) {
                        $storageErrors[] = $dir . ': ' . $moveErr->getMessage();
                    }
                }

                if (!$storedPath) {
                    throw new \RuntimeException('No se pudo almacenar la imagen. Falló file_put_contents. Detalles: ' . implode(' | ', $storageErrors));
                }
                
                Log::info('Foto guardada exitosamente', [
                    'path' => $storedPath,
                    'full_url' => '/api/profile/photo/' . $filename,
                ]);
                
                $validated['foto_perfil'] = $filename;
                unset($validated['foto_perfil_base64']);
            } catch (\Exception $e) {
                Log::error('Error al guardar foto', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
                return response()->json([
                    'message' => 'Error al guardar la imagen: ' . $e->getMessage()
                ], 500);
            }
        } else {
            // Si no se envía nueva foto, mantener la existente
            unset($validated['foto_perfil']);
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

    /**
     * Cambiar contraseña del usuario
     */
    public function changePassword(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:6', 'max:20', 'confirmed'],
        ], [
            'current_password.required' => 'La contraseña actual es requerida.',
            'new_password.required' => 'La nueva contraseña es requerida.',
            'new_password.min' => 'La nueva contraseña debe tener al menos 6 caracteres.',
            'new_password.max' => 'La nueva contraseña no puede tener más de 20 caracteres.',
            'new_password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        // Verificar que la contraseña actual sea correcta
        if (!Hash::check($validated['current_password'], $user->password)) {
            return response()->json([
                'message' => 'La contraseña actual es incorrecta.'
            ], 422);
        }

        // Verificar que la nueva contraseña sea diferente
        if (Hash::check($validated['new_password'], $user->password)) {
            return response()->json([
                'message' => 'La nueva contraseña debe ser diferente a la actual.'
            ], 422);
        }

        // Actualizar la contraseña
        $user->update([
            'password' => Hash::make($validated['new_password'])
        ]);

        return response()->json([
            'message' => 'Contraseña actualizada correctamente.'
        ]);
    }
}

