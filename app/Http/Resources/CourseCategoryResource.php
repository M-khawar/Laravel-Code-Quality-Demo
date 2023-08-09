<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseCategoryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            "uuid" => $this->uuid,
            "name" => $this->name,
            "has_access" => (boolean)$this->has_access,
            "description" => $this->description,
            "prohibited_message" => $this->prohibited_message,
        ];
    }
}
