<?php

namespace App\Http\Controllers\ShopManger;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrUpdateGroupOrderRequest;
use App\Http\Resources\GroupOrderResource;
use App\Models\GroupOrder;
use App\Models\Product;
use Illuminate\Http\Request;

class GroupOrderController extends Controller
{
    public function index(Request $request)
    {
        $shopId = $request->user()->shops->id;

        $groupOrders = GroupOrder::whereHas('product', function ($query) use ($shopId) {
            $query->where('shop_id', $shopId);
        })
        ->with(['product' => function ($query) use ($shopId) {
            $query->where('shop_id', $shopId);
        }])
        ->get();

        // فلترة النتائج اللي فيها product null
        $filtered = $groupOrders->filter(function ($groupOrder) {
            return $groupOrder->product !== null;
        });

        return GroupOrderResource::collection($filtered->values());
    }

    public function show($id)
    {
        $shopId = auth()->user()->shops->id;

        $groupOrder = GroupOrder::where('id', $id)
            ->whereHas('product', function ($query) use ($shopId) {
                $query->where('shop_id', $shopId);
            })
            ->with('product')
            ->first();

        if (!$groupOrder) {
            return response()->json([
                'success' => false,
                'message' => 'The request does not exist or you are not authorized to view it'
            ], 404);
        }

        return new GroupOrderResource($groupOrder);
    }

    public function store(StoreOrUpdateGroupOrderRequest $request)
    {
        $shopId = auth()->user()->shops->id;

        $product = Product::where('id', $request->product_id)
                          ->where('shop_id', $shopId)
                          ->firstOrFail();

        $groupOrder = GroupOrder::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'The group order was created successfully',
            'data' => new GroupOrderResource($groupOrder),
        ]);
    }

    public function update(StoreOrUpdateGroupOrderRequest $request, GroupOrder $groupOrder)
    {
        $this->authorizeGroupOrder($groupOrder);

        $groupOrder->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'The collaborative request has been successfully modified.',
            'data' => new GroupOrderResource($groupOrder),
        ]);
    }

    public function destroy(GroupOrder $groupOrder)
    {
        $this->authorizeGroupOrder($groupOrder);

        $groupOrder->delete();

        return response()->json([
            'success' => true,
            'message' => 'The shared request was successfully deleted'
        ]);
    }

    private function authorizeGroupOrder(GroupOrder $groupOrder)
    {
        $shopId = auth()->user()->shops->id;

        if ($groupOrder->product->shop_id !== $shopId) {
            abort(403, 'You are not authorized.');
        }
    }
}
