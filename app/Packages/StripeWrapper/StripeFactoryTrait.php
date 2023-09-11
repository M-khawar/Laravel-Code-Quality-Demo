<?php

namespace App\Packages\StripeWrapper;

use App\Packages\StripeWrapper\CardActions\{AddPaymentCardAction};
use App\Packages\StripeWrapper\SubscriptionActions\{
    BuySubscriptionAction,
    CancelSubscriptionAction,
    ChangeSubscriptionAction,
    ResumeSubscriptionAction
};

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

    public function addNewCard()
    {
        return app(AddPaymentCardAction::class);
    }
}
