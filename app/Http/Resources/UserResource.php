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
        $data = array(
            'uuid' => $this->uuid,
            'name' => $this->name,
            'email' => $this->email,
            'instagram' => $this->instagram,
            'affiliate_code' => $this->affiliate_code,
            'funnel_type' => $this->funnel_type,
            'phone' => $this->phone,
            'avatar' => $this->avatar_path,
            'joined_at' => $this->created_at->format('M d, Y'),
        );

        $settings = (new SettingResource($this->whenLoaded("settings")));
        $data = array_merge($data, $settings->jsonSerialize());

        $relations = array(
            'address' => new AddressResource($this->whenLoaded('address')),
            'card' => [
                'type' => @$this->pm_type,
                'last_four' => $this->pm_last_four ? Str::of($this->pm_last_four)->padLeft(19, '**** ') : null,
            ],
            'is_onboarding_completed' => $this->isOnboardingCompleted(),
            'onboarding_steps_state' => $this->onboardingStepsState,
            'advisor' => new AdvisorResource($this->whenLoaded('advisor')),
            'affiliate' => new AdvisorResource($this->whenLoaded('affiliate')),
            'promote_settings' => new PromoteSettingResource($this->whenLoaded('profile')),
            'master_class_funnel' => $this->masterClassFunnel(),
            'live_opportunity_call_funnel' => $this->liveOpportunityCallFunnel(),
            'notifications' => $this->notifications,
            'permissions' => $this->permissions,
            'has_active_subscription' => $this->has_active_subscription,
            'active_subscription' => new SubscriptionResource($this->whenLoaded('subscription')),
        );
        $data = array_merge($data, $relations);

        return $data;
    }

    protected function isOnboardingCompleted()
    {
        $stepsState = $this->onboardingStepsState;
        unset($stepsState[FB_GROUP_JOINED]); //excluding FB_GROUP_JOINED property from onboarding steps

        return in_array(false, array_values($stepsState)) ? false : true;
    }
}
