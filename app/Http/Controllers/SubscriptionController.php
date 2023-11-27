<?php

namespace App\Http\Controllers;

use App\Http\Resources\SubscriptionPlanResource;
use App\Http\Resources\SubscriptionResource;
use App\Models\SubscriptionPlan;
use App\Packages\StripeWrapper\StripeFactory;
use App\Packages\StripeWrapper\StripeFactoryTrait;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Exception\AuthenticationException;

class SubscriptionController extends Controller
{
    use StripeFactoryTrait {
        cancelSubscription as cancelStripeSubscription;
        resumeSubscription as resumeStripeSubscription;
        changeSubscriptionPlan as changeSubscription;
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
    public function getSubscriptionsForUser(Request $request)
    {
        try {
            Stripe::setApiKey(config('services.stripe.secret'));
    
            $userId = $request->input('user_id'); 
    
            
            $customer = \App\Models\User::find($userId)->stripe_id;
    
            
            $subscriptions = \Stripe\Subscription::all(['customer' => $customer]);
    
            return response()->json(['subscriptions' => $subscriptions]);
        } catch (AuthenticationException $e) {
           
            return response()->json(['error' => 'Authentication error. Check your API key.']);
        } catch (\Exception $e) {
            
            return response()->json(['error' => 'An error occurred while processing the request.']);
        }
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
        $plans->each->mapPrice();

        $subscriptionPlans = SubscriptionPlanResource::collection($plans);
        return response()->success(__('subscription.plans_retrieved.success'), ['subscription_plans' => $subscriptionPlans]);

    }

    public function changeSubscriptionPlan(Request $request)
    {
        try {
            $user = $request->user();
            $input = $request->input();

            $subscription = $this->changeSubscription()->handle($user, $input);
            $subscription->loadMissing('subscriptionPlan');

            $data = [
                'has_active_subscription' => $user->has_active_subscription,
                'active_subscription' => new SubscriptionResource($subscription),
            ];

            return response()->success(__('subscription.plan_changed.success'), $data);

        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function updatePaymentMethod(Request $request)
    {
        try {
            $user = $request->user();
            $input = $request->input();

            $card = $this->addNewCard()->handle($user, $input);

            return response()->success(__('subscription.payment_card.updated'), $card);

        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function resubscribe(Request $request)
    {
        try {
            $user = $request->user();
            $input = $request->input();

            $subscription = $this->resubscribeSubscription()->handle($user, $input);
            $subscription->loadMissing('subscriptionPlan');

            $user->refresh();

            $data = [
                'has_active_subscription' => $user->has_active_subscription,
                'active_subscription' => new SubscriptionResource($subscription),
            ];

            return response()->success(__('subscription.resubscribed.success', ['plan_interval' => $subscription?->subscriptionPlan?->meta['interval']]), $data);

        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }
}
