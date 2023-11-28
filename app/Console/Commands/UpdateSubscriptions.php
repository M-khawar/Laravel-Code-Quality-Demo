<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\User; // Adjust the namespace as needed
use Laravel\Cashier\Cashier;

class UpdateSubscriptions extends Command
{
    protected $signature = 'subscriptions:update';
    protected $description = 'Update subscriptions for active users';

    public function handle()
    {
        dd('yes');
        $activeUsers = User::whereHas('subscriptions', function ($query) {
            $query->where('stripe_status', 'active');
        })->get();

        foreach ($activeUsers as $user) {
            $subscription = $user->subscription('default');

            // Check if the user has an active subscription
            if ($subscription && $subscription->active()) {
                $endDate = Carbon::parse($subscription->ends_at);
                $remainingDays = now()->diffInDays($endDate);

                // Update the trial period for the remaining days
                $user->newSubscription('default', $subscription->stripe_plan)
                     ->trialDays($remainingDays)
                     ->create($user->defaultPaymentMethod()->id);

                $this->info("Updated subscription for user: {$user->name}");
            }
        }

        $this->info('Subscription update completed.');
    }
}
