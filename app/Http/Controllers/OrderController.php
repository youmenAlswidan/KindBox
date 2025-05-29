<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return response()->json(Order::with(['user', 'groupOrder'])->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'total_price' => 'required|numeric|min:0',
            'total_quantity' => 'required|integer|min:1',
            'group_orders_id' => 'nullable|exists:group_orders,id',
            'status' => 'required|string',
        ]);

        $order = Order::create($data);

        return response()->json($order, 201);
    }

    public function show(Order $order)
    {
        return response()->json($order->load(['user', 'groupOrder']));
    }

    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'total_price' => 'sometimes|numeric|min:0',
            'total_quantity' => 'sometimes|integer|min:1',
            'group_orders_id' => 'nullable|exists:group_orders,id',
            'status' => 'sometimes|string',
        ]);

        $order->update($data);

        return response()->json($order);
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return response()->json(null, 204);
    }
}