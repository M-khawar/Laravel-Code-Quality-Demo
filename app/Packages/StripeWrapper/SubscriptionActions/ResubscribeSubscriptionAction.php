<?php

namespace App\Packages\StripeWrapper\SubscriptionActions;

use App\Models\SubscriptionPlan;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Validator;

class ResubscribeSubscriptionAction extends StripeSubscriptionAbstract
{
    public function handle(User $user, array $data)
    {
        $this->resubscribeValidation($data)->validate();

        if ($user->subscribed($this->subscription_name) && !$user->subscription(config('cashier.subscription_name'))->ended()) {
            throw new Exception("User is already subscribed. To change the plan, please call the appropriate API");
        }

        $plan = SubscriptionPlan::byUUID($data["plan_id"])->firstOrFail();

        $subscription = $user->newSubscription($this->subscription_name, $plan->meta["stripe_price_id"])->add();
        $subscription->fill(['subscription_plan_id' => $plan->id])->save();

        return $subscription;
    }

    protected function resubscribeValidation(array $data)
    {
        return Validator::make($data, [
            'plan_id' => ['required', 'uuid', 'exists:' . SubscriptionPlan::class . ',uuid'],
        ]);
    }
}
