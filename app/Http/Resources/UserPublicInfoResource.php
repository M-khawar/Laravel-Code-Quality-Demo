<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserPublicInfoResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'email' => $this->email,
            'instagram' => $this->instagram,
            'affiliate_code' => $this->affiliate_code,
            'funnel_type' => $this->funnel_type,
            'phone' => $this->phone,
            'avatar' => $this->avatar_path,
            'address' => new AddressResource($this->whenLoaded('address')),
            'joined_at' => $this->created_at->format('M d, Y'),
            'advisor' => new AdvisorResource($this->whenLoaded('advisor')),
            'affiliate' => new AdvisorResource($this->whenLoaded('affiliate')),
        ];
    }
}
