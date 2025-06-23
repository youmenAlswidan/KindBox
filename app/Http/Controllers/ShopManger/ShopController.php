<?php

namespace App\Http\Controllers\ShopManger;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreOrUpdateShopRequest;
use App\Models\Shop;
use Illuminate\Support\Facades\Validator;
class ShopController extends Controller
{

    public function index()
    {
        $shops = Shop::where('user_id', auth()->id())->get();

        return response()->json($shops);
    }

      public function store(StoreOrUpdateShopRequest $request)
{
    $existingShop = Shop::where('user_id', auth()->id())->first();
    if ($existingShop) {
        return response()->json([
            'message' => 'You already have a shop. Each user can only create one shop.'
        ], 403);
    }

    $validated = $request->validated();

    if ($request->hasFile('image_shop')) {
        $validated['image_shop'] = $request->file('image_shop')->store('shops', 'public');
    }

    $validated['user_id'] = auth()->id();

    $shop = Shop::create($validated);

    return response()->json([
        'message' => 'The shop has been created successfully and is awaiting document verification.',
        'shop' => $shop
    ]);
}

  
      public function show($id)
    {
        $shop = Shop::findOrFail($id);

        if ($shop->user_id !== auth()->id()) {
          abort(403, 'You are not authorized.');
        }

        return response()->json($shop);
    }

public function update(StoreOrUpdateShopRequest $request, $id)
{
    $shop = Shop::findOrFail($id);

    if ($shop->user_id !== auth()->id()) {
        abort(403, 'You are not authorized.');
    }

    // البيانات المفلترة من الفورم ريكويست (تراعي الـ sometimes حسب قواعد التحقق)
    $validated = $request->validated();

    // التعامل مع رفع الصورة إذا كانت موجودة
    if ($request->hasFile('image_shop')) {
        $validated['image_shop'] = $request->file('image_shop')->store('shops', 'public');
    }

    $shop->update($validated);
    $shop->refresh();

    return response()->json([
        'message' => 'Update successfuly',
        'updated_data' => $validated,
        'shop_now' => $shop,
    ]);
}



     public function destroy($id)
    {
        $shop = Shop::findOrFail($id);

        if ($shop->user_id !== auth()->id()) {
            abort(403, 'You are not authorized.');
        }

        $shop->delete();

        return response()->json([
           'message' => 'The shop has been deleted successfully.',
        ]);
    }
}


     