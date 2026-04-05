<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FavoriteController extends Controller
{
    /**
     * Verificar si un lugar está en favoritos
     */
    public function check(Request $request, $placeId): JsonResponse
    {
        $user = $request->user();
        
        $favorite = Favorite::where('user_id', $user->id)
            ->where('place_id', $placeId)
            ->first();

        return response()->json([
            'is_favorite' => $favorite !== null,
            'favorite_id' => $favorite ? $favorite->id : null,
        ]);
    }

    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $favorites = Favorite::where('user_id', $user->id)
            ->with('place')
            ->get();

        return response()->json([
            'favorites' => $favorites->map(function ($favorite) {
                return [
                    'id' => $favorite->id,
                    'place_id' => $favorite->place_id,
                    'place' => $favorite->place ? [
                        'id' => $favorite->place->id,
                        'name' => $favorite->place->name,
                        'description' => $favorite->place->description,
                        'location' => $favorite->place->location,
                        'image' => $favorite->place->image,
                    ] : null,
                    'created_at' => $favorite->created_at,
                ];
            }),
            'count' => $favorites->count(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'place_id' => 'required|exists:places,id',
        ]);

        // Verificar si ya existe
        $existing = Favorite::where('user_id', $user->id)
            ->where('place_id', $data['place_id'])
            ->first();

        if ($existing) {
            return response()->json(['message' => 'Este lugar ya está en tus favoritos'], 422);
        }

        $favorite = Favorite::create([
            'user_id' => $user->id,
            'place_id' => $data['place_id'],
        ]);

        $favorite->load('place');

        return response()->json([
            'message' => 'Lugar agregado a favoritos exitosamente',
            'favorite' => [
                'id' => $favorite->id,
                'place_id' => $favorite->place_id,
                'place' => $favorite->place ? [
                    'id' => $favorite->place->id,
                    'name' => $favorite->place->name,
                ] : null,
            ],
        ], 201);
    }

    public function destroy(Request $request, $placeId): JsonResponse
    {
        $user = $request->user();

        $favorite = Favorite::where('user_id', $user->id)
            ->where('place_id', $placeId)
            ->first();

        if (!$favorite) {
            return response()->json(['message' => 'Favorito no encontrado'], 404);
        }

        $favorite->delete();

        return response()->json([
            'message' => 'Lugar eliminado de favoritos exitosamente',
            'place_id' => (int) $placeId,
        ]);
    }
}
