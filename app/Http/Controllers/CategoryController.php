<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // إرجاع كل الفئات
    public function index()
    {
        return response()->json(Category::all());
    }

    // إنشاء فئة جديدة
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|string|max:255',
        ]);

        $category = Category::create($data);

        return response()->json($category, 201);
    }

    // عرض فئة معينة
    public function show(Category $category)
    {
        return response()->json($category);
    }

    // تعديل فئة معينة
    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|string|max:255',
        ]);

        $category->update($data);

        return response()->json($category);
    }

    // حذف فئة معينة
    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json(null, 204);
    }
}