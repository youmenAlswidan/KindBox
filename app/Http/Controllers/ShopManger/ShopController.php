<?php

namespace App\Http\Controllers\ShopManger;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreOrUpdateShopRequest;
use App\Models\Shop;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\ShopResource;

class ShopController extends Controller
{
    public function index()
    {
        $shops = Shop::where('user_id', auth()->id())->get();

        return ShopResource::collection($shops);
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
            'shop' => new ShopResource($shop),
        ]);
    }

    public function show($id)
    {
        $shop = Shop::findOrFail($id);

        if ($shop->user_id !== auth()->id()) {
            abort(403, 'You are not authorized.');
        }

        return new ShopResource($shop);
    }

    public function update(StoreOrUpdateShopRequest $request, $id)
    {
        $shop = Shop::findOrFail($id);

        if ($shop->user_id !== auth()->id()) {
            abort(403, 'You are not authorized.');
        }

        $validated = $request->validated();

        if ($request->hasFile('image_shop')) {
            // حذف الصورة القديمة إن وجدت
            if ($shop->image_shop) {
                Storage::disk('public')->delete($shop->image_shop);
            }

            // رفع الصورة الجديدة
            $validated['image_shop'] = $request->file('image_shop')->store('shops', 'public');
        }

        $shop->update($validated);
        $shop->refresh();

        return response()->json([
            'message' => 'Update successfuly',
            'updated_data' => $validated,
            'shop_now' => new ShopResource($shop),
        ]);
    }

    public function destroy($id)
    {
        $shop = Shop::findOrFail($id);

        if ($shop->user_id !== auth()->id()) {
            abort(403, 'You are not authorized.');
        }

        // حذف الصورة من التخزين
        if ($shop->image_shop) {
            Storage::disk('public')->delete($shop->image_shop);
        }

        $shop->delete();

        return response()->json([
            'message' => 'The shop has been deleted successfully.',
        ]);
    }
}
