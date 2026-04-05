<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuarios;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Rules\AllowedEmailDomain;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function create()
    {
        return view('registro');
    }

    public function store(Request $request)
    {
        // Preparar datos de entrada
        $username = trim((string) $request->input('name', ''));
        $email = strtolower(trim((string) $request->input('email', '')));
        $password = (string) $request->input('password', '');
        $passwordConfirmation = (string) $request->input('password_confirmation', '');

        // Validar datos
        $validator = Validator::make([
            'name' => $username,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $passwordConfirmation,
        ], [
            'name' => ['required', 'string', 'max:255', Rule::unique('usuarios', 'name')],
            'email' => [
                'required',
                'string',
                'max:255',
                new AllowedEmailDomain(),
                Rule::unique('usuarios', 'email'),
            ],
            'password' => 'required|string|min:8|max:15|confirmed',
        ], [
            'name.required' => 'El nombre de usuario es obligatorio.',
            'name.string' => 'El nombre de usuario debe ser texto.',
            'name.max' => 'El nombre de usuario no puede tener más de 255 caracteres.',
            'name.unique' => 'Este nombre de usuario ya está en uso. Por favor elige otro.',

            'email.required' => 'El correo electrónico es obligatorio.',
            'email.max' => 'El correo electrónico no puede tener más de 255 caracteres.',
            'email.unique' => 'Este correo electrónico ya está registrado. Intenta iniciar sesión.',

            'password.required' => 'La contraseña es obligatoria.',
            'password.string' => 'La contraseña debe ser texto.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.max' => 'La contraseña no puede tener más de 15 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden. Por favor verifica.',
        ]);

        $validator->after(function ($validator) use ($username, $email) {

            if (Usuarios::query()->whereRaw('LOWER(name) = ?', [strtolower($username)])->exists()) {
                $validator->errors()->add('name', 'Este nombre de usuario ya está en uso. Por favor elige otro.');
            }

            if (Usuarios::query()->whereRaw('LOWER(email) = ?', [$email])->exists()) {
                $validator->errors()->add('email', 'Este correo electrónico ya está registrado. Intenta iniciar sesión.');
            }

        });

        $data = $validator->validate();

        // Crear usuario con los mismos campos que el seeder
        $user = Usuarios::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'telefono' => null,
            'foto_perfil' => null,
            'password' => Hash::make($data['password']),
            'is_admin' => 0,
            'tipo_usuario' => 'normal',
            'fecha_registro' => now(),
        ]);

        // Iniciar sesión automáticamente
        Auth::login($user);

        // Redirigir al dashboard
        return redirect()->route('dashboard')->with('status', 'Registro exitoso.');
    }
}