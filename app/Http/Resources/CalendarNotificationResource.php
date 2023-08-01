<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CalendarNotificationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->uuid,
            'type' => $this->type,
            'duration' => $this->duration,
            'duration_type' => $this->duration_type,
            'sent_status' => $this->sent_status,
        ];
    }
}
