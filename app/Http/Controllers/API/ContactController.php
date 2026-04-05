<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Rules\NoProfanity;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Contact;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    /**
     * Guardar un mensaje de contacto
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', new NoProfanity()],
            'email' => 'required|email|max:255',
            'phone' => ['required', 'string', 'max:20', new NoProfanity()],
            'message' => ['required', 'string', 'max:2000', new NoProfanity()],
        ], [
            'name.required' => 'El nombre es requerido.',
            'email.required' => 'El correo electrónico es requerido.',
            'email.email' => 'El correo electrónico debe ser válido.',
            'phone.required' => 'El teléfono es requerido.',
            'message.required' => 'El mensaje es requerido.',
            'message.max' => 'El mensaje no puede exceder los 2000 caracteres.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        // Obtener el usuario autenticado si existe (opcional)
        $userId = $request->user()?->id;

        // Crear el contacto en la base de datos
        $contact = Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'message' => $request->message,
            'user_id' => $userId,
        ]);

        return response()->json([
            'message' => 'Mensaje enviado correctamente. Nos pondremos en contacto contigo pronto.',
            'data' => [
                'id' => $contact->id,
                'name' => $contact->name,
                'email' => $contact->email,
                'phone' => $contact->phone,
                'created_at' => $contact->created_at,
            ]
        ], 201);
    }

    /**
     * Obtener todos los contactos (solo para administradores)
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        
        if (!$user || !$user->is_admin) {
            return response()->json(['message' => 'No autorizado'], 403);
        }
        
        $contacts = Contact::with('usuario:id,name,email')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($contacts);
    }

    /**
     * Obtener un contacto específico (solo para administradores)
     */
    public function show(Request $request, $id): JsonResponse
    {
        $user = $request->user();
        
        if (!$user || !$user->is_admin) {
            return response()->json(['message' => 'No autorizado'], 403);
        }
        
        $contact = Contact::with('usuario:id,name,email')->findOrFail($id);

        return response()->json($contact);
    }
}
