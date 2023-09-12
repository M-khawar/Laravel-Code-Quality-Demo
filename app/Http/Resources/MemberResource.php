<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MemberResource extends JsonResource
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
            "phone" => $this->phone,
            "instagram" => $this->instagram,
            "funnel_type" => $this->funnel_type,
            "achieved_badges" => @$this->achieved_badges,
            "join_date" => $this->created_at->toDateString(),
            "address" => new AddressResource($this->whenLoaded("address")),
            "affiliate" => new AffiliateResource($this->whenLoaded("affiliate")),
            "notifications" => new NotificationSettingsResource($this->whenLoaded("notificationSettings"))
        ];
    }
}
