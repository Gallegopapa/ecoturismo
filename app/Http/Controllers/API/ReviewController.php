<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Rules\NoProfanity;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    private function logRatingPayload(Request $request, string $action): void
    {
        Log::info('reviews.rating_payload', [
            'action' => $action,
            'path' => $request->path(),
            'user_id' => optional($request->user())->id,
            'place_id' => $request->input('place_id'),
            'ecohotel_id' => $request->input('ecohotel_id'),
            'rating' => $request->input('rating'),
            'calificacion' => $request->input('calificacion'),
            'puntuacion' => $request->input('puntuacion'),
        ]);
    }

    /**
     * Normaliza aliases comunes de la calificación para evitar pérdida de datos.
     */
    private function normalizeRating(Request $request): void
    {
        $rawRating = $request->input('rating', $request->input('calificacion', $request->input('puntuacion')));

        if ($rawRating === null || $rawRating === '') {
            return;
        }

        $request->merge([
            'rating' => (int) $rawRating,
        ]);
    }

    /**
     * Obtener todas las reseñas (público)
     */

    // Obtener todas las reseñas (público)
    public function all(): JsonResponse
    {
        $reviews = Review::with(['usuario:id,name,foto_perfil', 'place', 'ecohotel'])
            ->orderBy('fecha_comentario', 'desc')
            ->get();
        return response()->json($reviews);
    }

    // Obtener reseñas de un lugar o ecohotel
    public function index(Request $request, $type, $id): JsonResponse
    {
        if ($type === 'place') {
            $reviews = Review::where('place_id', $id)
                ->with('usuario:id,name,foto_perfil')
                ->orderBy('fecha_comentario', 'desc')
                ->get();
        } elseif ($type === 'ecohotel') {
            $reviews = Review::where('ecohotel_id', $id)
                ->with('usuario:id,name,foto_perfil')
                ->orderBy('fecha_comentario', 'desc')
                ->get();
        } else {
            return response()->json(['message' => 'Tipo inválido'], 400);
        }
        $avg = $reviews->avg('rating');
        $count = $reviews->count();
        return response()->json([
            'reviews' => $reviews,
            'average' => $avg,
            'count' => $count
        ]);
    }

    // Crear reseña para lugar o ecohotel
    public function store(Request $request): JsonResponse
    {
        $this->normalizeRating($request);
        $this->logRatingPayload($request, 'store');
        $user = $request->user();
        
        $messages = [
            'comment.max' => 'El comentario no puede exceder los 500 caracteres.',
            'rating.required' => 'La calificación es obligatoria.',
            'rating.integer' => 'La calificación debe ser un número.',
            'place_id.required_without' => 'El lugar es obligatorio si no se especifica ecohotel.',
            'ecohotel_id.required_without' => 'El ecohotel es obligatorio si no se especifica lugar.',
        ];
        
        $data = $request->validate([
            'place_id' => 'required_without:ecohotel_id|nullable|exists:places,id',
            'ecohotel_id' => 'required_without:place_id|nullable|exists:ecohotels,id',
            'rating' => 'required|integer',
            'comment' => ['nullable', 'string', 'max:500', new \App\Rules\NoProfanity()],
        ], $messages);

        $review = Review::create([
            'user_id' => $user->id,
            'place_id' => $data['place_id'] ?? null,
            'ecohotel_id' => $data['ecohotel_id'] ?? null,
            'rating' => $data['rating'],
            'comment' => $data['comment'] ?? null,
            'fecha_comentario' => now(),
        ]);
        
        $review->load('usuario:id,name,foto_perfil');
        return response()->json($review, 201);
    }
}
