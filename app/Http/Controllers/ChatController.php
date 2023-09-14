<?php

namespace App\Http\Controllers;

use App\Events\ChatMessageSent;
use App\Models\Chat;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function fetchMessages()
    {
        return Chat::with('user')->get();
    }

    public function sendMessage(Request $request)
    {
        $user = currentUser();

        $message = $user->chat()->create([
            'message' => $request->input('message')
        ]);

        broadcast(new ChatMessageSent($user, $message))->toOthers();

        return ['status' => 'Message Sent!'];
    }
}
