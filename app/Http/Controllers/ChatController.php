<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\ChatRepositoryInterface;
use App\Http\Resources\ChatResource;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function __construct(public ChatRepositoryInterface $chatRepository)
    {
    }

    public function index()
    {
        try {
            $messages = $this->chatRepository->fetchMessages();
            $messages = (ChatResource::collection($messages))->response()->getData(true);

            return response()->success(__("messages.chat_message.fetched"), $messages);

        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function store(Request $request)
    {
        try {
            $data = $request->input();
            $message = $this->chatRepository->sendChatMessage($data);
            $message = new ChatResource($message);

            return response()->success(__("messages.chat_message.sent"), $message);

        } catch (\Exception $exception) {
            return $this->handleException($exception);
        }
    }
}
