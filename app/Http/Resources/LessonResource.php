<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LessonResource extends JsonResource
{
    public function toArray($request)
    {
//        return parent::toArray($request);

        return [
            "uuid" => $this->uuid,
            "name" => $this->name,
            "description" => $this->description,
            "resources" => $this->resources,
            "position" => $this->position,
            "video_uuid"=> $this->video->uuid,
            "video_slug"=> $this->video->slug,
            "video_source"=> $this->video->source,
            "video_link"=> $this->video->link,
        ];
    }
}
