<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    public function __construct($resource)
    {
        parent::__construct($resource);

        self::wrap("collection");
    }

    public function toArray($request)
    {
        return [
            "uuid" => $this->uuid,
            "name" => $this->name,
            "thumbnail" => $this->thumbnail_path,
            "description" => $this->description,
            "sort" => $this->sort,
            "last_updated" => $this->updated_at->format('M d, Y'),
        ];
    }
}
