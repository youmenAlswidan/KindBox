<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // دالة ثابتة لإرسال إشعار
    public static function notifyUser($userId, $message)
    {
        return Notification::create([
            'user_id' => $userId,
            'message' => $message,
            'is_read' => false,
        ]);
    }

    // دالة إرسال إشعار عبر API (بما فيها التحقق)
    public function sendNotification(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000',
        ]);

        $notification = self::notifyUser($request->user_id, $request->message);

        return response()->json([
            'message' => 'تم إرسال الإشعار بنجاح.',
            'notification' => $notification,
        ]);
    }
}
