<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class AdminProductController extends Controller
{

   
    public function index()
{
    $products = Product::with('shop')->latest()->get();
    return view('admin.products.index', compact('products'));
}


public function show($id)
{
    $product = Product::with('shop')->findOrFail($id);
    return view('admin.products.show', compact('product'));
}


public function destroy($id)
{
    $product = Product::findOrFail($id);
    if ($product->image && Storage::disk('public')->exists($product->image)) {
        Storage::disk('public')->delete($product->image);
    }

    $product->delete();
return redirect()->route('admin.products.index')->with('success', 'The product was deleted.');}

}