<?php

namespace App\Packages\StripeWrapper;

use App\Packages\StripeWrapper\SubscriptionActions\BuySubscriptionAction;
use App\Packages\StripeWrapper\SubscriptionActions\CancelSubscriptionAction;
use App\Packages\StripeWrapper\SubscriptionActions\ChangeSubscriptionAction;
use App\Packages\StripeWrapper\SubscriptionActions\ResumeSubscriptionAction;

trait StripeFactoryTrait
{

    public function buySubscription()
    {
        return app(BuySubscriptionAction::class);
    }

    public function cancelSubscription()
    {
        return app(CancelSubscriptionAction::class);
    }

    public function resumeSubscription()
    {
        return app(ResumeSubscriptionAction::class);
    }

    public function changeSubscriptionPlan()
    {
        return app(ChangeSubscriptionAction::class);
    }
}
