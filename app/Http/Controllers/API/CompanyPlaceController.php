<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Place;
use App\Rules\NoProfanity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompanyPlaceController extends Controller
{
    private function ensureCompanyAccess(Request $request, Place $place): ?JsonResponse
    {
        $user = $request->user();

        if (!$user || !$user->isCompanyUser()) {
            return response()->json([
                'message' => 'No tienes permisos para acceder a este recurso.'
            ], 403);
        }

        $hasAccess = $user->placesManaged()->where('places.id', $place->id)->exists();
        if (!$hasAccess) {
            return response()->json([
                'message' => 'No tienes permisos para gestionar este lugar.'
            ], 403);
        }

        return null;
    }

    /**
     * Listar lugares gestionados por el usuario empresa.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user->isCompanyUser()) {
            return response()->json([
                'message' => 'No tienes permisos para acceder a este recurso.'
            ], 403);
        }

        $places = $user->placesManaged()
            ->select('places.id', 'places.name', 'places.location')
            ->orderBy('places.name')
            ->get();

        return response()->json($places);
    }

    /**
     * Obtener un lugar gestionado por el usuario empresa.
     */
    public function show(Request $request, Place $place): JsonResponse
    {
        if ($response = $this->ensureCompanyAccess($request, $place)) {
            return $response;
        }

        $place->load(['categories', 'schedules', 'ecohoteles']);

        return response()->json($place);
    }

    /**
     * Actualizar un lugar gestionado por el usuario empresa.
     */
    public function update(Request $request, Place $place): JsonResponse
    {
        if ($response = $this->ensureCompanyAccess($request, $place)) {
            return $response;
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', new NoProfanity()],
            'description' => ['nullable', 'string', new NoProfanity()],
            'location' => ['nullable', 'string', 'max:255', new NoProfanity()],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'telefono' => ['nullable', 'string', 'max:20', new NoProfanity()],
            'email' => 'nullable|email|max:255',
            'sitio_web' => 'nullable|url|max:255',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'ecohoteles' => 'array',
            'ecohoteles.*' => 'nullable|exists:ecohotels,id',
        ], [
            'name.required' => 'El nombre del lugar es requerido.',
            'image.image' => 'El archivo debe ser una imagen.',
            'image.max' => 'La imagen no puede exceder 5MB.',
            'latitude.numeric' => 'La latitud debe ser un número.',
            'latitude.between' => 'La latitud debe estar entre -90 y 90.',
            'longitude.numeric' => 'La longitud debe ser un número.',
            'longitude.between' => 'La longitud debe estar entre -180 y 180.',
            'categories.array' => 'Las categorías deben ser un array.',
            'categories.*.exists' => 'Una o más categorías no existen.',
        ]);

        if ($request->hasFile('image')) {
            if ($place->image) {
                $oldFileName = basename(parse_url($place->image, PHP_URL_PATH));
                if ($oldFileName && Storage::disk('public')->exists('places/' . $oldFileName)) {
                    Storage::disk('public')->delete('places/' . $oldFileName);
                }
            }

            $image = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('places', $filename, 'public');
            $data['image'] = '/storage/' . $path;
        }

        $categories = $data['categories'] ?? null;
        $ecohoteles = $data['ecohoteles'] ?? [];
        unset($data['categories'], $data['ecohoteles']);

        $place->update($data);

        if ($categories !== null) {
            $place->categories()->sync($categories ?: []);
        }
        // Sincronizar ecohoteles (array vacío = sin ecohoteles)
        $place->ecohoteles()->sync($ecohoteles);

        $place->load(['categories', 'ecohoteles']);

        return response()->json([
            'message' => 'Lugar actualizado correctamente.',
            'place' => $place
        ]);
    }

    /**
     * Eliminar un lugar gestionado por el usuario empresa.
     */
    public function destroy(Request $request, Place $place): JsonResponse
    {
        if ($response = $this->ensureCompanyAccess($request, $place)) {
            return $response;
        }

        if ($place->image && strpos($place->image, 'storage/places/') !== false) {
            $imagePath = str_replace(asset(''), '', $place->image);
            $imagePath = str_replace('storage/', '', $imagePath);
            if (Storage::disk('public')->exists('places/' . basename($imagePath))) {
                Storage::disk('public')->delete('places/' . basename($imagePath));
            }
        }

        $place->delete();

        return response()->json([
            'message' => 'Lugar eliminado correctamente.'
        ]);
    }
}
