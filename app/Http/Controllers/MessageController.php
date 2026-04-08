<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Enviar un mensaje/comentario
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
        ], [
            'message.required' => 'El mensaje no puede estar vacío.',
            'message.max' => 'El mensaje no puede exceder los 1000 caracteres.',
        ]);

        // Aquí podrías guardar el mensaje en la base de datos
        // Por ejemplo:
        // Message::create([
        //     'user_id' => Auth::id(),
        //     'content' => $validated['message'],
        // ]);

        // O enviarlo por correo electrónico
        // Mail::to('admin@example.com')->send(new ContactMessage($validated['message']));

        return response()->json([
            'message' => 'Mensaje enviado correctamente.',
            'data' => $validated
        ]);
    }
}
