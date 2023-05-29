<?php

namespace App\Packages\StripeWrapper;

use App\Packages\StripeWrapper\SubscriptionActions\BuySubscriptionAction;

trait StripeFactoryTrait
{

    public function buySubscription()
    {
        return app(BuySubscriptionAction::class);
    }
}
