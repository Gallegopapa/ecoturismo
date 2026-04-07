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

    // AC3: List Favorites
    public function index(Request $request)
    {
        $user = $request->user();
        $favorites = \App\Models\Favorite::with('place')
            ->where('user_id', $user->id)
            ->get()
            ->map(function ($fav) {
                // Return the place details
                return $fav->place;
            });

        return response()->json($favorites);
    }

    // AC4: Check specific Favorite
    public function checkFavorite($placeId, Request $request)
    {
        $user = $request->user();
        
        $isFavorite = \App\Models\Favorite::where('user_id', $user->id)
            ->where('place_id', $placeId)
            ->exists();

        return response()->json(['is_favorite' => $isFavorite]);
    }
}
