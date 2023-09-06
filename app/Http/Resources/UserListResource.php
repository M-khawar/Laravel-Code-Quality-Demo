<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserListResource extends JsonResource
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
            "email" => $this->email,
            "avatar" => $this?->avatar?->media_path,
        ];
    }
}
