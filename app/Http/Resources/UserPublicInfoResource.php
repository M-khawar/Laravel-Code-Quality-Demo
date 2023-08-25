<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserPublicInfoResource extends JsonResource
{
    public function toArray($request)
    {
        $data = [
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

        //this whenLoad filter not working, if relation not loaded then it is loading implicitly
        $roles= $this->whenLoaded("roles", $this->mapRoles(), []);
        $data = array_merge($data, ["roles" => $roles]);

        return $data;
    }

    protected function mapRoles()
    {
        return $this->roles->map(fn($role, $index) => $role->uuid)->toArray();
    }
}
