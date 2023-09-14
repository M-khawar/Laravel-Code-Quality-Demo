<?php

namespace App\Repositories;

use App\Contracts\Repositories\ChatRepositoryInterface;
use App\Events\ChatMessageSent;
use Illuminate\Database\Eloquent\Model;

class ChatRepository implements ChatRepositoryInterface
{
    private Model $chatModel;

    public function __construct(Model $chatModel)
    {
        $this->chatModel = $chatModel;
    }

    public function fetchMessages()
    {
        return $this->chatModel::with('user')->latest()->paginate();
    }

    public function sendChatMessage(array $data)
    {
        $user = currentUser();

        $message = $user->chat()->create([
            'message' => $data['message']
        ]);

        $message->setRelation('user', $user);

        broadcast(new ChatMessageSent($user, $message))->toOthers();

        return $message;
    }
}
