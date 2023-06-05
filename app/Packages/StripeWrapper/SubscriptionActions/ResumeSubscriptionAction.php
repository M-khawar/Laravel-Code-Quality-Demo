<?php

namespace App\Packages\StripeWrapper\SubscriptionActions;

use App\Models\User;
use Exception;

class ResumeSubscriptionAction extends StripeSubscriptionAbstract
{
    public function handle(User $user)
    {
//        $user = currentUser();

        if (!$user->subscribed($this->subscription_name)) {
            throw new Exception("You are not subscribed to any subscription yet");
        }

        if (!$user->subscription($this->subscription_name)->onGracePeriod()) {
            throw new Exception("You are not on grace period yet. Subscription can only be resumed on grace period");
        }

        return $user->subscription($this->subscription_name)->resume();
    }
}
