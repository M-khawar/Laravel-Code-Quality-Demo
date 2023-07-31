<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CalendarResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            "id" => $this->uuid,
            "title" => $this->title,
            "description" => $this->description,
            "link" => $this->link,
            "color" => $this->color,
            "display_date" => $this->display_date,
            "start_time" => $this->start_time,
            "end_time" => $this->end_time,
            "start" => $this->calendar_timestamp,
            "allowed_audience_roles" => RoleResource::collection($this->whenLoaded("allowedAudienceRoles"))
        ];
    }
}
