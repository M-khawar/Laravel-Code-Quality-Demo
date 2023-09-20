<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NoteResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            "uuid" => $this->uuid,
            "note" => $this->note,
            "last_updated" => $this->updated_at->format('D d M, Y g:i A'),
        ];
    }
}
