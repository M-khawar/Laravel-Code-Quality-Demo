<?php

namespace App\Http\Controllers;

use App\Packages\StripeWrapper\StripeFactory;
use App\Packages\StripeWrapper\StripeFactoryTrait;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    use StripeFactoryTrait {
        buySubscription as buyStripeSubscription;
    }

    public function createPaymentMethod(StripeFactory $stripeFactory)
    {
        $paymentMethod = $stripeFactory->createPaymentMethod()->id;
        return response()->success("Testing Payment Method Created", ['payment_method' => $paymentMethod]);
    }

    public function buySubscription(Request $request)
    {
        $user = $request->user();
        $data = $request->input();
        $subscription = $this->buyStripeSubscription()->handle($user, $data);
        dd($subscription);
    }
}
