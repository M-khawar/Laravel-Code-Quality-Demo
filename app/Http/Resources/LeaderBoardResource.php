<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LeaderBoardResource extends JsonResource
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
            'avatar' => $this?->avatar?->media_path,
            'joined_at' => $this->created_at->format('M d, Y'),
            "visits_count" => $this->visits_count,
            "leads_count" => $this->leads_count,
            "members_count" => $this->members_count,
            "achieved_level" => $this->achieved_level,
        ];
    }
}
