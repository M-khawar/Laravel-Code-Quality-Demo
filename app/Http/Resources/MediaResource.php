<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            "uuid" => $this->uuid,
            "source" => $this->source,
            "path" => $this->media_path,
            "archived" => $this->archived
        ];
    }
}
