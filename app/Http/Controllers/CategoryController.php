<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Place;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show(Category $category)
    {
        $places = $category->places()->with('reviews')->get();
        return view('category.show', compact('category', 'places'));
    }
}
