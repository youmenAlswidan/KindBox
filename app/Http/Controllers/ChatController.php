<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;

use Illuminate\Http\Request;
use App\Models\ChatHistory;

class ChatController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate([
            'question' => 'required|string',
        ]);

        $user = $request->user();

        // التحقق من كونه طالب
        if ($user->role_id != 3) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // إرسال السؤال إلى Python
        $response = Http::timeout(60)->post('http://127.0.0.1:5000/api/chat',[
            'question' => $request->question
        ]);

        $answer = $response->json('answer');

        // تخزين السجل
        ChatHistory::create([
            'user_id' => $user->id,
            'question' => $request->question,
            'answer' => $answer
        ]);

        return response()->json(['answer' => $answer]);
    }

    public function history(Request $request)
    {
        $user = $request->user();

        if ($user->role_id != 3) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($user->chatHistories()->latest()->get());
    }
}
