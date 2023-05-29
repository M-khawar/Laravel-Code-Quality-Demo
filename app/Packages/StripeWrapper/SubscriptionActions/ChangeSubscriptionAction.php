<?php

namespace App\Packages\StripeWrapper\SubscriptionActions;

use App\Models\{SubscriptionPlan, User};
use Exception;

class ChangeSubscriptionAction extends StripeSubscriptionAbstract
{
    public function handle(User $user, array $data)
    {
//        $user = currentUser();

        if (!$user->subscribed($this->subscription_name)) {
            throw new Exception("You are not subscribed to any subscription yet");
        }

        $plan = SubscriptionPlan::byUUID($data["plan_id"])->firstOrFail();

        if ($user->subscribedToPrice($plan->meta["stripe_price_id"], $this->subscription_name)) {
            throw new Exception("You are already susbcribed to this plan");
        }

        $subscription = $user->subscription($this->subscription_name)->swap([$plan->meta["stripe_price_id"]]);
        $subscription->fill(['subscription_plan_id' => $plan->id])->save();

        return $subscription;
}
}
