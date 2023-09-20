<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LeadResource extends JsonResource
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
            "instagram" => $this->instagram,
            "funnel_type" => $this->funnel_type,
            "status" => $this->status,
            "join_date" => $this->created_at->toDateString(),
            "affiliate" => new AffiliateResource($this->whenLoaded("affiliate")),
            "advisor" => new AffiliateResource($this->whenLoaded("advisor")),
        ];
    }
}
