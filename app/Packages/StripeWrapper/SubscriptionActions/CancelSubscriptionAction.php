<?php

namespace App\Packages\StripeWrapper\SubscriptionActions;

use App\Models\User;
use Exception;

class CancelSubscriptionAction extends StripeSubscriptionAbstract
{

    public function handle(User $user)
    {
//        $user = currentUser();

        if (!$user->subscribed($this->subscription_name)) {
            throw new Exception("You are not subscribed to any subscription yet");
        }

        if ($user->subscription($this->subscription_name)->onGracePeriod()) {
            throw new Exception("You had already unsubscribed from this subscription, you are currently on grace period");
        }

        return $user->subscription($this->subscription_name)->cancel();
    }
}
