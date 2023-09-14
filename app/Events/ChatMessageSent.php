<?php

namespace App\Events;

use Illuminate\Database\Eloquent\Model;
use App\Models\{Chat, User};
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\{Channel, PresenceChannel, PrivateChannel};
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatMessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public $message)
    {
    }

    public function broadcastOn()
    {
        return new PrivateChannel('global-chat');
    }

    public function broadcastAs()
    {
        return 'chat_message.sent';
    }
}
