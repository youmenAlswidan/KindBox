<?php

namespace App\Http\Controllers;

use App\Models\GroupOrder;
use Illuminate\Http\Request;

class GroupOrderController extends Controller
{
    public function index()
    {
        return response()->json(GroupOrder::with('product')->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'status' => 'required|string',
            'current_user_count' => 'required|integer|min:0',
            'require_user_count' => 'required|integer|min:1',
            'dead_line' => 'required|date',
        ]);

        $groupOrder = GroupOrder::create($data);

        return response()->json($groupOrder, 201);
    }

    public function show(GroupOrder $groupOrder)
    {
        return response()->json($groupOrder->load('product'));
    }

    public function update(Request $request, GroupOrder $groupOrder)
    {
        $data = $request->validate([
            'status' => 'sometimes|string',
            'current_user_count' => 'sometimes|integer|min:0',
            'require_user_count' => 'sometimes|integer|min:1',
            'dead_line' => 'sometimes|date',
        ]);

        $groupOrder->update($data);

        return response()->json($groupOrder);
    }

    public function destroy(GroupOrder $groupOrder)
    {
        $groupOrder->delete();

        return response()->json(null, 204);
    }
}