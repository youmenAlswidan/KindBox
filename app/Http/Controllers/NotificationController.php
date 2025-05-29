<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        return response()->json(Notification::with('user')->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'required|string', // تم التعديل هنا
        ]);

        $notification = Notification::create($data);

        return response()->json($notification, 201);
    }

    public function show(Notification $notification)
    {
        return response()->json($notification->load('user'));
    }

    public function update(Request $request, Notification $notification)
    {
        $data = $request->validate([
            'is_read' => 'required|boolean',
            'message' => 'sometimes|string', // في حال أردت تعديل الرسالة
        ]);

        $notification->update($data);

        return response()->json($notification);
    }

    public function destroy(Notification $notification)
    {
        $notification->delete();

        return response()->json(null, 204);
    }
}