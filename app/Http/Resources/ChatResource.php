<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
{
    public function __construct($resource)
    {
        parent::__construct($resource);

        self::wrap("collection");
    }

    public function toArray($request)
    {
        return [
            'uuid'  =>$this->uuid,
            'message' => $this->message,
            'date' => $this->created_at->format('D d M, Y'),
            'time' => $this->created_at->format('g:i A'),
            'timestamp' => $this->created_at,
            'user' => new ChatUserResource($this->whenLoaded("user"))
        ];
    }
}
