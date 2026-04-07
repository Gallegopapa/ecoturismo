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
}

