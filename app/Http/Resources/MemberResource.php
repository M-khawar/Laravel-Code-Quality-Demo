<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MemberResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            "uuid" => $this->uuid,
            "name" => $this->name,
            "email" => $this->email,
            "instagram" => $this->instagram,
            "funnel_type" => $this->funnel_type,
            "join_date" => $this->created_at->toDateString(),
            "affiliate" => new AffiliateResource($this->whenLoaded("affiliate")),
        ];
    }
}
