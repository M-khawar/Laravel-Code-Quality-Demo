<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
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
            'address' => new AddressResource($this->whenLoaded('address')),
            'card' => [
                'type' => @$this->pm_type,
                'last_four' => $this->pm_last_four ? Str::of($this->pm_last_four)->padLeft(19, '**** ') : null,
            ],
            'onboarding_steps_state' => $this->onboardingStepsState,
            'advisor' => new AdvisorResource($this->whenLoaded('advisor')),
            'active_subscription' => new SubscriptionResource($this->whenLoaded('subscription')),
        ];
    }
}
