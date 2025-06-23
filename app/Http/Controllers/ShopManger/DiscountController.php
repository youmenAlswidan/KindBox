<?php

namespace App\Http\Controllers\ShopManger;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrUpdateDiscountRequest;
use App\Models\Discount;
use App\Models\Product;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $shopIds = $user->shops->pluck('id');

        $discounts = Discount::whereHas('product', function ($query) use ($shopIds) {
            $query->whereIn('shop_id', $shopIds);
        })->with('product')->get();

        return response()->json(['success' => true, 'data' => $discounts]);
    }

    public function store(StoreOrUpdateDiscountRequest $request)
    {
        $product = Product::where('id', $request->product_id)
                          ->whereIn('shop_id', auth()->user()->shops->pluck('id'))
                          ->firstOrFail();

        $discount = Discount::create($request->validated());

        return response()->json(['success' => true, 'message' =>'Discount added successfully', 'data' => $discount]);
    }
    public function show($id)
{
    
    $discount = Discount::findOrFail($id);

    $this->authorizeDiscount($discount);
    return response()->json([
        'success' => true,
        'data' => $discount
    ]);
}


    public function update(StoreOrUpdateDiscountRequest $request, Discount $discount)
    {
        $this->authorizeDiscount($discount);

        $discount->update($request->validated());

        return response()->json(['success' => true, 'message' =>'Discount modified successfully', 'data' => $discount]);
    }

    public function destroy(Discount $discount)
    {
        $this->authorizeDiscount($discount);

        $discount->delete();

        return response()->json(['success' => true, 'message' => ' The discount was successfully removed.']);
    }

    private function authorizeDiscount(Discount $discount)
    {
        $userShopIds = auth()->user()->shops->pluck('id');

        if (!in_array($discount->product->shop_id, $userShopIds->toArray())) {
           abort(403, 'You are not authorized.');
        }
    }
} 