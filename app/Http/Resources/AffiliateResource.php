<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AffiliateResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'email' => $this->email,
            'instagram' => $this->instagram,
            'affiliate_code' => $this->affiliate_code,
            'phone' => $this->phone,
            'avatar' => $this->avatar_path,
        ];
    }
}
