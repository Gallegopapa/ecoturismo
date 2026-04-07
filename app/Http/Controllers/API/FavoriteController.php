<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'place_id' => 'required|exists:places,id'
        ]);

        $user = $request->user();

        // AC2: Duplicate Prevention (422)
        $exists = Favorite::where('user_id', $user->id)
                          ->where('place_id', $request->place_id)
                          ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Este lugar ya está en tus favoritos.',
                'errors' => ['place_id' => ['Ya está en favoritos']]
            ], 422);
        }

        // Guardamos el favorito
        $favorite = Favorite::create([
            'user_id' => $user->id,
            'place_id' => $request->place_id
        ]);

        return response()->json($favorite, 201);
    }
}
