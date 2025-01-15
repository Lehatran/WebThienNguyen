<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function getCategories()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    public function getCategoryById($id)
    {
        $category = Category::find($id);
        if ($category) {
            return [
                'name' => $category->name,
            ];
        }
        return null;
    }
}
