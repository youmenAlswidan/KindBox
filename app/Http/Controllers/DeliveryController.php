<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    /**
     * جلب كل عمليات التوصيل
     */
    public function index()
    {
        return response()->json(Delivery::with(['order', 'vehicle'])->get());
    }

    /**
     * تخزين عملية توصيل جديدة
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'status' => 'required|string',
            'failure_reason' => 'nullable|string',
            'attempted_at' => 'required|date',
        ]);

        $delivery = Delivery::create($data);

        return response()->json($delivery, 201);
    }

    /**
     * جلب عملية توصيل واحدة بناءً على ID
     */
    public function show(Delivery $delivery)
    {
        return response()->json($delivery->load(['order', 'vehicle']));
    }

    /**
     * تحديث بيانات عملية التوصيل
     */
    public function update(Request $request, Delivery $delivery)
    {
        $data = $request->validate([
            'status' => 'sometimes|string',
            'failure_reason' => 'sometimes|string',
            'attempted_at' => 'sometimes|date',
        ]);

        $delivery->update($data);

        return response()->json($delivery);
    }

    /**
     * حذف عملية توصيل
     */
    public function destroy(Delivery $delivery)
    {
        $delivery->delete();

        return response()->json(null, 204);
    }
}