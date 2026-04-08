<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * Mostrar el formulario de login
     */
    public function showLoginForm()
    {
        return view('login');
    }

    /**
     * Procesar intento de login
     */
    public function login(Request $request)
    {
        $email = strtolower(trim((string) $request->input('email', '')));
        $password = (string) $request->input('password', '');
        $request->merge([
            'email' => $email,
            'password' => $password,
        ]);

        // Validar campos requeridos (usamos email para login)
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'regex:/^[A-Z0-9._%+-]+@gmail\\.com$/i'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'El correo es requerido.',
            'email.email' => 'Introduce un correo válido.',
            'email.regex' => 'Solo se permiten correos con dominio @gmail.com.',
            'password.required' => 'La contraseña es requerida.',
        ]);

        // Buscar usuario por email
        $user = Usuarios::query()->whereRaw('LOWER(email) = ?', [$email])->first();

        // Validar credenciales
        if (!$user) {
            return back()->withErrors([
                'credentials' => 'Las credenciales proporcionadas son incorrectas.',
            ])->onlyInput('email');
        }

        if (!Hash::check($password, $user->password)) {
            return back()->withErrors([
                'credentials' => 'Las credenciales proporcionadas son incorrectas.',
            ])->onlyInput('email');
        }

        // Autenticar usuario
        Auth::login($user, $request->has('remember')); // Agregar soporte para "remember me"
        $request->session()->regenerate();

        // Redirigir a la raíz y dejar que React Router maneje la navegación
        return redirect('/')->with('status', '¡Bienvenido de nuevo!');
    }

    /**
     * Cerrar sesión
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('pagcentral');
    }
}