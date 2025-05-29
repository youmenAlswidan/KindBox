<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return response()->json(Product::with(['category', 'shop'])->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'product_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'shop_id' => 'required|exists:shops,id',
            'image' => 'nullable|string|max:255',
            'has_group_purchase' => 'required|boolean',
            'group_price' => 'nullable|numeric|min:0',
            'group_min_users' => 'nullable|integer|min:1',
        ]);

        $product = Product::create($data);

        return response()->json($product, 201);
    }

    public function show(Product $product)
    {
        return response()->json($product->load(['category', 'shop']));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'product_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'shop_id' => 'required|exists:shops,id',
            'image' => 'nullable|string|max:255',
            'has_group_purchase' => 'required|boolean',
            'group_price' => 'nullable|numeric|min:0',
            'group_min_users' => 'nullable|integer|min:1',
        ]);

        $product->update($data);

        return response()->json($product);
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json(null, 204);
    }
}