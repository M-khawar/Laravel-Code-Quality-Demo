<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
//        dd($this);
        return [
            'plan_id' => $this->subscriptionPlan?->uuid,
            'subscription_id' => $this->id,
            'title' => $this->subscriptionPlan?->name,
            'amount' => $this->subscriptionPlan?->amount,
            'interval' => @$this->subscriptionPlan->meta['interval'],
            'status'=> $this->stripe_status,
            'trail_end_at' => $this->trial_ends_at,
            'subscribed_at' => $this->created_at,
        ];
    }
}
