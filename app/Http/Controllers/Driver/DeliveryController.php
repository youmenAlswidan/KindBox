<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeliveryController extends Controller
{
    // عرض الطلبات المخصصة للسائق
    public function index()
    {
        dd(Auth::user());
        $driverId = Auth::id();

        $deliveries = Delivery::whereHas('order', function ($query) use ($driverId) {
            $query->where('user_id', $driverId);
        })->with('order')->get();

        return response()->json([
            'status' => true,
            'deliveries' => $deliveries
        ]);
    }

    // تحديث حالة الطلب
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:assigned,in transit,delivered,failed,returned',
            'failure_reason' => 'nullable|string'
        ]);

        $delivery = Delivery::findOrFail($id);
        $delivery->status = $request->status;

        if ($request->status === 'failed') {
            $delivery->failure_reason = $request->failure_reason;
        }

        $delivery->attempted_at = now();
        $delivery->save();

        return response()->json([
            'message' => 'تم تحديث حالة الطلب بنجاح',
            'delivery' => $delivery
        ]);
    }

    // تحديد أن الطلب تم توصيله
    public function markAsDelivered($id)
    {
        $delivery = Delivery::findOrFail($id);
        $delivery->status = 'delivered';
        $delivery->attempted_at = now();
        $delivery->save();

        return response()->json([
            'message' => 'تم توصيل الطلب بنجاح',
            'delivery' => $delivery
        ]);
    }

    // تتبع الموقع (اختياري - تحتاج عمود location في جدول السائقين أو users)
    public function updateLocation(Request $request)
    {
        $request->validate([
            'latitude' => 'required',
            'longitude' => 'required'
        ]);

        $user = Auth::user();
        $user->latitude = $request->latitude;
        $user->longitude = $request->longitude;
        $user->save();

        return response()->json([
            'message' => 'تم تحديث الموقع'
        ]);
    }
}