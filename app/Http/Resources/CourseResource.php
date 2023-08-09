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
           "uuid"=> $this->uuid,
           "name"=> $this->name,
           "thumbnail"=> $this->thumbnail,
           "description"=> $this->description,
           "sort"=> $this->sort,
           "last_updated_at"=> $this->updated_at,
        ];
    }
}
