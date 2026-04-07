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

        // Guardamos el favorito sin chequeos por ahora (AC1)
        // AC2 will handle duplicates
        $favorite = Favorite::create([
            'user_id' => $user->id,
            'place_id' => $request->place_id
        ]);

        return response()->json($favorite, 201);
    }
}
