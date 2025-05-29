<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    // إرجاع كل المحلات
    public function index()
    {
        return response()->json(Shop::with('user', 'category')->get());
    }

    // إنشاء محل جديد
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'shop_name' => 'required|string|max:255',
            'image_shop' => 'nullable|string|max:255',
            'discription_shop' => 'nullable|string',
            'location' => 'required|string',
            'statues' => 'required|string',
            'phone_number' => 'required|string|max:15',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $shop = Shop::create($data);

        return response()->json($shop, 201);
    }

    // عرض محل معين
    public function show(Shop $shop)
    {
        return response()->json($shop->load('user', 'category'));
    }

    // تعديل محل معين
    public function update(Request $request, Shop $shop)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'shop_name' => 'required|string|max:255',
            'image_shop' => 'nullable|string|max:255',
            'discription_shop' => 'nullable|string',
            'location' => 'required|string',
            'statues' => 'required|string',
            'phone_number' => 'required|string|max:15',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $shop->update($data);

        return response()->json($shop);
    }

    // حذف محل معين
    public function destroy(Shop $shop)
    {
        $shop->delete();

        return response()->json(null, 204);
    }
}