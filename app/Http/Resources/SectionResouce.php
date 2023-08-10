<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SectionResouce extends JsonResource
{
    public function toArray($request)
    {
        return [
            "uuid" => $this->uuid,
            "name" => $this->name,
            "description" => $this->description,
            "position" => $this->position,
            "lessons" => LessonResource::collection($this->whenLoaded("lessons")),
        ];
    }
}
