<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Rules\NoProfanity;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Obtener todas las categorías (público)
     */
    public function index(): JsonResponse
    {
        $categories = Category::with('places')->orderBy('name', 'asc')->get();
        return response()->json($categories);
    }

    /**
     * Obtener una categoría específica con sus lugares (público)
     */
    public function show(Category $category): JsonResponse
    {
        $category->load('places');
        return response()->json($category);
    }

    /**
     * Crear una nueva categoría (solo admin)
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name', new NoProfanity()],
            'description' => ['nullable', 'string', new NoProfanity()],
            'icon' => 'nullable|string|max:255',
        ], [
            'name.required' => 'El nombre de la categoría es requerido.',
            'name.unique' => 'Esta categoría ya existe.',
        ]);

        $data['slug'] = Str::slug($data['name']);
        $category = Category::create($data);

        return response()->json($category, 201);
    }

    /**
     * Actualizar una categoría (solo admin)
     */
    public function update(Request $request, Category $category): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name,' . $category->id, new NoProfanity()],
            'description' => ['nullable', 'string', new NoProfanity()],
            'icon' => 'nullable|string|max:255',
        ], [
            'name.required' => 'El nombre de la categoría es requerido.',
            'name.unique' => 'Esta categoría ya existe.',
        ]);

        $data['slug'] = Str::slug($data['name']);
        $category->update($data);

        return response()->json($category);
    }

    /**
     * Eliminar una categoría (solo admin)
     */
    public function destroy(Category $category): JsonResponse
    {
        $category->delete();
        return response()->json(['message' => 'Categoría eliminada correctamente'], 200);
    }
}

