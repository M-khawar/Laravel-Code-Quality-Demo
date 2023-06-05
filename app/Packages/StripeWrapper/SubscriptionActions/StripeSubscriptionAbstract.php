<?php

namespace App\Packages\StripeWrapper\SubscriptionActions;

abstract class StripeSubscriptionAbstract
{

    public function __construct(protected $subscription_name = null)
    {
        $this->subscription_name = config('cashier.subscription_name');
    }

    public function getSubscriptionName():string
    {
        return $this->subscription_name;
    }
}
