<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class SubscriptionResource extends JsonResource
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
            'plan_id' => $this->subscriptionPlan?->uuid,
            'subscription_id' => $this->uuid,
            'title' => $this->subscriptionPlan?->name,
            'amount' => $this->subscriptionPlan?->amount,
            'interval' => @$this->subscriptionPlan->meta['interval'],
            'status' => $this->status(),
            'trail_end_at' => $this->trial_ends_at,
            'subscription_ends_at' => $this->ends_at,
            'subscribed_at' => $this->created_at,
        ];
    }

    protected function status()
    {
        if ($this->ends_at && $this->stripe_status == 'active') {
            return 'GRACE_PERIOD';

        } elseif (!$this->ends_at && $this->stripe_status == 'active') {
            return 'ACTIVE';

        } else {
            return Str::upper($this->stripe_status);
        }
    }
}
