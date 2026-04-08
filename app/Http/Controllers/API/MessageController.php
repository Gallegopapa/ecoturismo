<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Rules\NoProfanity;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MessageController extends Controller
{
    /**
     * Enviar un mensaje/comentario de contacto
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', new NoProfanity()],
            'email' => 'required|email|max:255',
            'message' => ['required', 'string', 'max:1000', new NoProfanity()],
            'subject' => ['nullable', 'string', 'max:255', new NoProfanity()],
        ], [
            'name.required' => 'El nombre es requerido.',
            'email.required' => 'El correo electrónico es requerido.',
            'email.email' => 'El correo electrónico debe ser válido.',
            'message.required' => 'El mensaje no puede estar vacío.',
            'message.max' => 'El mensaje no puede exceder los 1000 caracteres.',
        ]);

        // Aquí podrías guardar el mensaje en la base de datos
        // Por ejemplo, si tuvieras una tabla 'messages':
        // Message::create([
        //     'name' => $validated['name'],
        //     'email' => $validated['email'],
        //     'subject' => $validated['subject'] ?? 'Sin asunto',
        //     'message' => $validated['message'],
        //     'user_id' => $request->user()?->id,
        // ]);

        // O enviarlo por correo electrónico
        // Mail::to('admin@example.com')->send(new ContactMessage($validated));

        return response()->json([
            'message' => 'Mensaje enviado correctamente. Nos pondremos en contacto contigo pronto.',
            'data' => [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'subject' => $validated['subject'] ?? 'Sin asunto',
            ]
        ], 201);
    }
}

