<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Place;
use App\Rules\NoProfanity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    private function logRatingPayload(Request $request, string $action): void
    {
        Log::info('reviews.rating_payload', [
            'action' => $action,
            'path' => $request->path(),
            'user_id' => optional(Auth::user())->id,
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

    public function store(Request $request)
    {
        $this->normalizeRating($request);
        $this->logRatingPayload($request, 'store');
        $messages = [
            'comment.max' => 'El comentario no puede exceder los 500 caracteres.',
            'rating.required' => 'La calificación es obligatoria.',
            'rating.integer' => 'La calificación debe ser un número.',
            'rating.min' => 'La calificación mínima es 1.',
            'rating.max' => 'La calificación máxima es 5.',
        ];

        $data = $request->validate([
            'place_id' => 'required|exists:places,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => ['nullable', 'string', 'max:500', new NoProfanity()],
        ], $messages);

        // Verificar que el usuario no haya comentado antes este lugar
        $existingReview = Review::where('user_id', Auth::id())
            ->where('place_id', $data['place_id'])
            ->first();

        if ($existingReview) {
            return back()->withErrors(['review' => 'Ya has comentado este lugar. Solo puedes comentar una vez por lugar.']);
        }

        Review::create([
            'user_id' => Auth::id(),
            'place_id' => $data['place_id'],
            'rating' => $data['rating'],
            'comment' => $data['comment'] ?? null,
            'fecha_comentario' => now(),
        ]);

        return back()->with('status', 'Comentario agregado correctamente.');
    }

    public function destroy(Review $review)
    {
        // Permitir eliminar si es el autor o si el usuario es admin
        $user = Auth::user();
        if ($review->user_id !== $user->id && !$user->is_admin) {
            abort(403);
        }

        $review->delete();
        return back()->with('status', 'Comentario eliminado.');
    }

    public function update(Request $request, Review $review)
    {
        $this->normalizeRating($request);
        $this->logRatingPayload($request, 'update');
        // Solo el autor puede editar su reseña
        if ($review->user_id !== Auth::id()) {
            abort(403);
        }

        $messages = [
            'comment.max' => 'El comentario no puede exceder los 500 caracteres.',
            'rating.required' => 'La calificación es obligatoria.',
            'rating.integer' => 'La calificación debe ser un número.',
            'rating.min' => 'La calificación mínima es 1.',
            'rating.max' => 'La calificación máxima es 5.',
            'place_id.exists' => 'El lugar seleccionado no existe.',
        ];

        $data = $request->validate([
            'place_id' => 'sometimes|exists:places,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => ['nullable', 'string', 'max:500', new NoProfanity()],
        ], $messages);

        if (array_key_exists('place_id', $data) && $data['place_id'] !== $review->place_id) {
            $alreadyReviewed = Review::where('user_id', Auth::id())
                ->where('place_id', $data['place_id'])
                ->where('id', '!=', $review->id)
                ->exists();

            if ($alreadyReviewed) {
                return back()->withErrors([
                    'review' => 'Ya has comentado este lugar. Solo puedes comentar una vez por lugar.'
                ]);
            }

            $review->place_id = $data['place_id'];
        }

        $review->update([
            'rating' => $data['rating'],
            'comment' => $data['comment'] ?? null,
            'fecha_comentario' => now(),
        ]);

        return back()->with('status', 'Comentario actualizado.');
    }
}
