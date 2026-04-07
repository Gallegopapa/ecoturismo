<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PlaceAdminController extends Controller
{
    public function index()
    {
        $places = Place::orderBy('id', 'desc')->get();
        return view('admin.places', compact('places'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'image' => 'nullable|string|max:500',
        ]);

        Place::create($data);

        return redirect()->route('admin.places.index')->with('status', 'Lugar creado correctamente.');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB máximo
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('places', $filename, 'public');
            // Guardar como ruta relativa (independiente de APP_URL)
            $url = '/storage/' . $path;

            return response()->json([
                'success' => true,
                'url' => $url,
                'message' => 'Imagen subida correctamente'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No se recibió ninguna imagen'
        ], 400);
    }

    public function update(Request $request, Place $place)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'image' => 'nullable|string|max:500',
        ]);

        // Solo actualizar la imagen si se envía una nueva (no null/vacío)
        if (empty($data['image'])) {
            unset($data['image']);
        } elseif ($data['image'] !== $place->image) {
            // Si la imagen es diferente y existe una anterior, eliminar la anterior
            if ($place->image && strpos($place->image, 'storage/places/') !== false) {
                $oldImagePath = str_replace(asset(''), '', $place->image);
                $oldImagePath = str_replace('storage/', '', $oldImagePath);
                if (\Storage::disk('public')->exists('places/' . basename($oldImagePath))) {
                    \Storage::disk('public')->delete('places/' . basename($oldImagePath));
                }
            }
        }

        $place->update($data);

        // Sincronizar categorías solo si vienen en la request
        if ($request->has('categories')) {
            $categories = $request->input('categories', []);
            // Si es string vacío o array vacío, limpiar relación
            if (is_array($categories)) {
                $place->categories()->sync($categories);
            } elseif ($categories === '' || $categories === null) {
                $place->categories()->sync([]);
            }
        }

        return redirect()->route('admin.places.index')->with('status', 'Lugar actualizado correctamente.');
    }

    public function destroy(Place $place)
    {
        // Eliminar imagen si existe y está en storage
        if ($place->image && strpos($place->image, 'storage/places/') !== false) {
            $imagePath = str_replace(asset(''), '', $place->image);
            $imagePath = str_replace('storage/', '', $imagePath);
            Storage::disk('public')->delete('places/' . basename($imagePath));
        }

        $place->delete();

        return redirect()->route('admin.places.index')->with('status', 'Lugar eliminado.');
    }
}

