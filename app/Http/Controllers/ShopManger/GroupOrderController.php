<?php

namespace App\Http\Controllers\ShopManger;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrUpdateGroupOrderRequest;
use App\Models\GroupOrder;
use App\Models\Product;
use Illuminate\Http\Request;

class GroupOrderController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $shopIds = $user->shops->pluck('id');

        $groupOrders = GroupOrder::whereHas('product', function ($query) use ($shopIds) {
            $query->whereIn('shop_id', $shopIds);
        })->with('product')->get();

        return response()->json(['success' => true, 'data' => $groupOrders]);
    }

    public function store(StoreOrUpdateGroupOrderRequest $request)
    {
        $product = Product::where('id', $request->product_id)
                          ->whereIn('shop_id', auth()->user()->shops->pluck('id'))
                          ->firstOrFail();

        $groupOrder = GroupOrder::create($request->validated());

return response()->json(['success' => true, 'message' => 'The group order was created successfully', 'data' => $groupOrder]);    }
public function show($id)
{
    $userShopIds = auth()->user()->shops->pluck('id');

    $groupOrder = GroupOrder::where('id', $id)
        ->whereHas('product', function ($query) use ($userShopIds) {
            $query->whereIn('shop_id', $userShopIds);
        })->with('product')->first();

    if (!$groupOrder) {
        
return response()->json(['success' => false, 'message' => 'The request does not exist or you are not authorized to view it'], 404);    }

    return response()->json(['success' => true, 'data' => $groupOrder]);
}



    public function update(StoreOrUpdateGroupOrderRequest $request, GroupOrder $groupOrder)
    {
        $this->authorizeGroupOrder($groupOrder);

        $groupOrder->update($request->validated());

        return response()->json(['success' => true, 'message' => 'The collaborative request has been successfully modified.', 'data' => $groupOrder]);
    }

    public function destroy(GroupOrder $groupOrder)
    {
        $this->authorizeGroupOrder($groupOrder);

        $groupOrder->delete();

return response()->json(['success' => true, 'message' => 'The shared request was successfully deleted']);    }

    private function authorizeGroupOrder(GroupOrder $groupOrder)
    {
        $userShopIds = auth()->user()->shops->pluck('id');

        if (!in_array($groupOrder->product->shop_id, $userShopIds->toArray())) {
          abort(403, 'You are not authorized.');
        }
    }
}
