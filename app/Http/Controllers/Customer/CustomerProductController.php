<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\Customer\CustomerProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\GroupOrder;
use App\Models\Discount;
use App\Http\Resources\Customer\CustomerProductDetailResource;
use App\Models\Category;


class CustomerProductController extends Controller
{
    public function index(Request $request)
{
    $query = Product::with(['groupOrders', 'discount']);

    // 🔍 فلترة البحث بالاسم أو الوصف
    if ($request->has('search') && $request->search != '') {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('product_name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    // 🔃 فلترة الترتيب
    if ($request->has('sort')) {
        if ($request->sort === 'latest') {
            $query->orderBy('created_at', 'desc');
        } elseif ($request->sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($request->sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        }
    }

    $products = $query->get();

    return CustomerProductResource::collection($products);
}




    public function show($id)
    {
        $product = Product::with(['discount', 'shop', 'ratings.user'])->findOrFail($id);
        return new CustomerProductDetailResource($product);
    }


    public function groupPurchaseProducts()
    {
        $products = Product::where('has_group_purchase', true)->get();

        return CustomerProductResource::collection($products);
    }


    public function productsByCategory($categoryId)
    {
        $category = Category::findOrFail($categoryId);

        $products = $category->products()->with(['discount', 'groupOrders'])->get();

        return CustomerProductResource::collection($products);
    }
}
