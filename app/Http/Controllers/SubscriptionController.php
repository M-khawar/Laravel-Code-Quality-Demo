<?php

namespace App\Http\Controllers;

use App\Http\Resources\SubscriptionResource;
use App\Packages\StripeWrapper\StripeFactory;
use App\Packages\StripeWrapper\StripeFactoryTrait;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    use StripeFactoryTrait {
        cancelSubscription as cancelStripeSubscription;
        resumeSubscription as resumeStripeSubscription;
    }

    public function createPaymentMethod(StripeFactory $stripeFactory)
    {
        $paymentMethod = $stripeFactory->createPaymentMethod()->id;
        return response()->success("Testing Payment Method Created", ['payment_method' => $paymentMethod]);
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
}
