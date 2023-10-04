<?php

namespace App\Packages\StripeWrapper\SubscriptionActions;

use App\Packages\StripeWrapper\Contracts\HasPaidTrail;
use App\Packages\StripeWrapper\StripeFactory;
use App\Models\{Subscription, SubscriptionPlan, User};
use Illuminate\Support\Facades\Validator;
use Exception;

class BuySubscriptionAction extends StripeSubscriptionAbstract
{
    public function handle(User $user, array $data)
    {

        $this->buySubscriptionValidation($data)->validate();

//        $user = currentUser();

        if ($user->subscribed($this->subscription_name)) {
            throw new Exception("User is already subscribed. To change the plan, please call the appropriate API");
        }


        $plan = SubscriptionPlan::byUUID($data["plan_id"])->firstOrFail();
        $payment_method_id = $data["payment_method_id"];

        //create stripe customer
        $user->createOrGetStripeCustomer();

        //attach payment method with customer and set it as default payment method
        $user->addPaymentMethod($payment_method_id);
        $user->updateDefaultPaymentMethod($payment_method_id);


        //user is not eligible for a trial if he once was an active subscriber
        if ((!empty($data["on_trial"]) && $data["on_trial"] == 1) && $user->subscription($this->subscription_name) && $user->subscription($this->subscription_name)->ended()) {
            throw new Exception("You are not eligible for trial because you once was an active subscriber");
        }

        $subscription = null;

        //(!empty($data["on_trial"]) && $data["on_trial"] == 1)
        if (@$plan->meta["interval"] && $plan->meta["interval"] == Subscription::PLAN_INTERVAL_MONTH) {

            // deducting paid trail payment
            if ($user instanceof HasPaidTrail) $this->applyPaidTrailCharges($user);

            //create subscription with trial
            $subscription = $user->newSubscription($this->subscription_name, $plan->meta["stripe_price_id"])
                ->trialDays(7)
                ->add();

        } else {
            //create subscription without trial
            $subscription = $user->newSubscription($this->subscription_name, $plan->meta["stripe_price_id"])
                ->add();
        }

        $subscription->fill(['subscription_plan_id' => $plan->id])->save();

        return $subscription;
    }

    protected function buySubscriptionValidation(array $data)
    {
        return Validator::make($data, [
            'plan_id' => ['required', 'uuid', 'exists:' . SubscriptionPlan::class . ',uuid'],
            'payment_method_id' => 'required'
        ]);
    }

    protected function applyPaidTrailCharges($user)
    {
        $trailPrice = StripeFactory::usdToCents(1);
        $user->invoiceFor("Paid 7 days trail at $1 only", $trailPrice);
    }
}
