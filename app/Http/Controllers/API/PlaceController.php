<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Rules\NoProfanity;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Place;
use App\Models\Category;

class PlaceController extends Controller
{
    /**
     * Obtener opciones mínimas de lugares para selects.
     */
    public function options(): JsonResponse
    {
        $places = Place::query()
            ->select(['id', 'name', 'location'])
            ->orderBy('name', 'asc')
            ->get();

        return response()->json($places);
    }

    /**
     * Obtener todos los lugares (público)
     */
    public function index(Request $request): JsonResponse
    {
        $query = Place::query();
        
        // Filtrar por categoría si se proporciona
        if ($request->has('category_id')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('categories.id', $request->category_id);
            });
        }
        
        // Búsqueda por nombre
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }
        
        // IMPORTANTE: Seleccionar explícitamente TODOS los campos incluyendo description
        $places = $query->select(['id', 'name', 'description', 'location', 'image', 'latitude', 'longitude', 'telefono', 'email', 'sitio_web', 'created_at', 'updated_at'])
            ->with(['categories', 'reviews.usuario:id,name,foto_perfil'])
            ->orderBy('name', 'asc')
            ->get();
        
        // Agregar información de rating a cada lugar
        $places->transform(function($place) {
            $place->average_rating = round($place->reviews->avg('rating') ?? 0, 1);
            $place->reviews_count = $place->reviews->count();
            return $place;
        });
        
        return response()->json($places);
    }

}