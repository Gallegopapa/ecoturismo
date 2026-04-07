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
     * Eliminar un ecohotel
     */
    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $ecohotel = Ecohotel::find($id);
        if (!$ecohotel) {
            return response()->json(['message' => 'Ecohotel no encontrado'], 404);
        }
        $ecohotel->delete();
        return response()->json(['message' => 'Ecohotel eliminado correctamente.']);
    }

    /**
     * Crear un nuevo ecohotel
     */
    public function store(Request $request): JsonResponse
    {
        \Log::info('REQUEST COMPLETO STORE', ['all' => $request->all()]);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', new NoProfanity()],
            'description' => ['nullable', 'string', new NoProfanity()],
            'location' => ['required', 'string', 'max:255', new NoProfanity()],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'telefono' => ['nullable', 'string', 'max:20', new NoProfanity()],
            'email' => 'nullable|email|max:255',
            'sitio_web' => 'nullable|url|max:255',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'places' => 'nullable|array',
            'places.*' => 'exists:places,id',
        ], [
            'name.required' => 'El nombre del ecohotel es requerido.',
            'location.required' => 'La ubicación es requerida.',
            'latitude.required' => 'La latitud es requerida.',
            'latitude.numeric' => 'La latitud debe ser un número.',
            'latitude.between' => 'La latitud debe estar entre -90 y 90.',
            'longitude.required' => 'La longitud es requerida.',
            'longitude.numeric' => 'La longitud debe ser un número.',
            'longitude.between' => 'La longitud debe estar entre -180 y 180.',
            'image.image' => 'El archivo debe ser una imagen.',
            'image.max' => 'La imagen no puede exceder 5MB.',
            'email.email' => 'El email debe ser válido.',
            'sitio_web.url' => 'El sitio web debe ser una URL válida.',
            'categories.array' => 'Las categorías deben ser un array.',
            'categories.*.exists' => 'Una o más categorías no existen.',
            'places.array' => 'Los lugares deben ser un array.',
            'places.*.exists' => 'Uno o más lugares no existen.',
        ]);

        // Manejar subida de imagen
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('ecohotels', $filename, 'public');
            $data['image'] = '/storage/' . $path;
        }

        $categories = $data['categories'] ?? null;
        $places = $data['places'] ?? null;
        \Log::info('STORE - Campo places recibido', ['places' => $places]);
        unset($data['categories'], $data['places']);

        $ecohotel = Ecohotel::create($data);

        // Asociar categorías si se proporcionan
        if ($categories !== null) {
            $ecohotel->categories()->sync($categories);
        }
        // Asociar lugares si se proporcionan
        \Log::info('STORE - Antes de sync', ['places' => $places]);
        if ($places !== null) {
            $ecohotel->places()->sync($places);
        }
        \Log::info('STORE - Después de sync', ['ecohotel_places' => $ecohotel->places()->pluck('places.id')->toArray()]);

        $ecohotel->load(['categories', 'places']);

        return response()->json([
            'message' => 'Ecohotel creado correctamente.',
            'ecohotel' => $ecohotel
        ], 201);
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

    /**
     * Actualizar un ecohotel
     */
    public function update(Request $request, Ecohotel $ecohotel): JsonResponse
    {
        \Log::info('REQUEST COMPLETO UPDATE', ['all' => $request->all()]);
        
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', new NoProfanity()],
            'description' => ['nullable', 'string', new NoProfanity()],
            'location' => ['required', 'string', 'max:255', new NoProfanity()],
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'telefono' => ['nullable', 'string', 'max:20', new NoProfanity()],
            'email' => 'nullable|email|max:255',
            'sitio_web' => 'nullable|url|max:255',
            'image' => 'nullable', // Puede venir como archivo o como string si no cambia
            'categories' => 'nullable|array',
            'categories.*' => 'nullable|exists:categories,id',
            'places' => 'nullable|array',
            'places.*' => 'nullable|exists:places,id',
        ], [
            'name.required' => 'El nombre del ecohotel es requerido.',
            'location.required' => 'La ubicación es requerida.',
            'latitude.required' => 'La latitud es requerida.',
            'latitude.numeric' => 'La latitud debe ser un número.',
            'latitude.between' => 'La latitud debe estar entre -90 y 90.',
            'longitude.required' => 'La longitud es requerida.',
            'longitude.numeric' => 'La longitud debe ser un número.',
            'longitude.between' => 'La longitud debe estar entre -180 y 180.',
            'email.email' => 'El email debe ser válido.',
            'sitio_web.url' => 'El sitio web debe ser una URL válida.',
            'categories.array' => 'Las categorías deben ser un array.',
            'categories.*.exists' => 'Una o más categorías no existen.',
            'places.array' => 'Los lugares deben ser un array.',
            'places.*.exists' => 'Uno o más lugares no existen.',
        ]);

        // Manejar actualización de imagen
        if ($request->hasFile('image')) {
            // Eliminar imagen anterior si existe física
            if ($ecohotel->getRawOriginal('image')) {
                $oldPath = $ecohotel->getRawOriginal('image');
                $oldFileName = basename(parse_url($oldPath, PHP_URL_PATH));
                if ($oldFileName && Storage::disk('public')->exists('ecohotels/' . $oldFileName)) {
                    Storage::disk('public')->delete('ecohotels/' . $oldFileName);
                }
            }

            $image = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('ecohotels', $filename, 'public');
            $data['image'] = '/storage/' . $path;
        } else {
            // Si no se subió una nueva, quitamos 'image' del array para no sobreescribir con null
            // a menos que explícitamente se quiera borrar (pero en este panel suele ser persistente)
            unset($data['image']);
        }

        $categories = $data['categories'] ?? [];
        $places = $data['places'] ?? [];
        
        // Actualizar datos principales (excepto relaciones)
        $ecohotel->update(collect($data)->except(['categories', 'places'])->toArray());

        // Filtrar ids vacíos o nulos y sincronizar
        $categoriesSync = array_filter($categories, fn($id) => !empty($id) && is_numeric($id));
        $placesSync = array_filter($places, fn($id) => !empty($id) && is_numeric($id));

        $ecohotel->categories()->sync($categoriesSync);
        $ecohotel->places()->sync($placesSync);

        $ecohotel->load(['categories', 'places']);

        return response()->json([
            'message' => 'Ecohotel actualizado correctamente.',
            'ecohotel' => $ecohotel
        ]);
    }
}
