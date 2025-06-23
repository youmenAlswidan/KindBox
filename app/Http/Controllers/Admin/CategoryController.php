<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories,name',
            'image' => 'nullable|image|max:2048',  
        ]);

        $data = ['name' => $request->name];

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public');
            $data['image'] = $imagePath;
        }

        Category::create($data);

return redirect()->route('admin.categories.index')->with('success', 'Category added successfully.');    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|unique:categories,name,' . $category->id,
            'image' => 'nullable|image|max:2048',
        ]);

        $data = ['name' => $request->name];

        if ($request->hasFile('image')) {
            
            if ($category->image && \Storage::disk('public')->exists($category->image)) {
                \Storage::disk('public')->delete($category->image);
            }

            $imagePath = $request->file('image')->store('categories', 'public');
            $data['image'] = $imagePath;
        }

        $category->update($data);

return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');    }

    public function destroy(Category $category)
    {
        
        if ($category->image && \Storage::disk('public')->exists($category->image)) {
            \Storage::disk('public')->delete($category->image);
        }

        $category->delete();

return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');    }
}
