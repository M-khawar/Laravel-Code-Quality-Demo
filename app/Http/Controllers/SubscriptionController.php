<?php

namespace App\Http\Controllers;

use App\Http\Resources\SubscriptionPlanResource;
use App\Http\Resources\SubscriptionResource;
use App\Models\SubscriptionPlan;
use App\Packages\StripeWrapper\StripeFactory;
use App\Packages\StripeWrapper\StripeFactoryTrait;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    use StripeFactoryTrait {
        cancelSubscription as cancelStripeSubscription;
        resumeSubscription as resumeStripeSubscription;
    }

    public function createClientSecret(StripeFactory $stripeFactory)
    {
        try {
            $clientSecret = $stripeFactory->createClientSecret()->client_secret;
            return response()->success(__('subscription.client_secret.success'), ['client_secret' => $clientSecret]);

        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function createPaymentMethod(StripeFactory $stripeFactory)
    {
        $paymentMethod = $stripeFactory->createPaymentMethod()->id;
        return response()->success(__('subscription.test.payment_method.success'), ['payment_method' => $paymentMethod]);
    }

    public function cancelSubscription(Request $request)
    {
        try {
            $user = $request->user();
            $subscription = $this->cancelStripeSubscription()->handle($user);
            $subscription->loadMissing('subscriptionPlan');

            $data = [
                'active_subscription' => new SubscriptionResource($subscription),
            ];

            return response()->success(__('subscription.cancelled.success'), $data);

        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function resumeSubscription(Request $request)
    {
        try {
            $user = $request->user();
            $subscription = $this->resumeStripeSubscription()->handle($user);
            $subscription->loadMissing('subscriptionPlan');

            $data = [
                'active_subscription' => new SubscriptionResource($subscription),
            ];

            return response()->success(__('subscription.resumed.success'), $data);

        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function getSubscriptionPlans()
    {
        $plans = SubscriptionPlan::all();
        $subscriptionPlans = SubscriptionPlanResource::collection($plans);
        return response()->success(__('subscription.plans_retrieved.success'), ['subscription_plans' => $subscriptionPlans]);

    }
}
