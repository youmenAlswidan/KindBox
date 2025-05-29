<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    public function index()
    {
        return response()->json(OrderItem::with(['order', 'product'])->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'price_item' => 'required|numeric|min:0',
            'quantity_item' => 'required|integer|min:1',
        ]);

        $item = OrderItem::create($data);

        return response()->json($item, 201);
    }

    public function show(OrderItem $orderItem)
    {
        return response()->json($orderItem->load(['order', 'product']));
    }

    public function update(Request $request, OrderItem $orderItem)
    {
        $data = $request->validate([
            'price_item' => 'sometimes|numeric|min:0',
            'quantity_item' => 'sometimes|integer|min:1',
        ]);

        $orderItem->update($data);

        return response()->json($orderItem);
    }

    public function destroy(OrderItem $orderItem)
    {
        $orderItem->delete();

        return response()->json(null, 204);
    }
}