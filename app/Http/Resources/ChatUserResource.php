<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChatUserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'email' => $this->email,
            'avatar' => $this?->avatar?->media_path,
            'joined_at' => $this->created_at->format('M d, Y'),
        ];
    }
}
