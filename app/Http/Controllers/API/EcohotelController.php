<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Ecohotel;
use App\Rules\NoProfanity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EcohotelController extends Controller
{
    /**
     * Listar todos los ecohoteles (para admin)
     */
    public function index(): JsonResponse
    {
        $ecohotels = Ecohotel::with(['categories', 'places'])->orderBy('created_at', 'desc')->get();
        // Agregar promedio y cantidad de reseñas a cada ecohotel
        $ecohotels = $ecohotels->map(function ($ecohotel) {
            $reviews = $ecohotel->reviews;
            $count = $reviews->count();
            $average = $count > 0 ? round($reviews->avg('rating'), 1) : null;
            $ecohotel->reviews_count = $count;
            $ecohotel->average_rating = $average;
            return $ecohotel;
        });
        return response()->json($ecohotels);
    }


    /**
     * Mostrar un ecohotel específico
     */
    public function show($id): JsonResponse
    {
        try {
            $ecohotel = Ecohotel::with(['categories', 'places.reviews'])->findOrFail($id);
            // Agregar promedio y cantidad de reseñas a cada lugar relacionado
            if ($ecohotel->places) {
                $ecohotel->places->transform(function($place) {
                    $place->average_rating = round($place->reviews->avg('rating') ?? 0, 1);
                    $place->reviews_count = $place->reviews->count();
                    return $place;
                });
            }
            return response()->json($ecohotel);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Ecohotel no encontrado',
                'error' => true
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al cargar el ecohotel: ' . $e->getMessage(),
                'error' => true
            ], 500);
        }
    }


}
