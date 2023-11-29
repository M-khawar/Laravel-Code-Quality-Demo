<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Laravel\Cashier\Cashier;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class UpdateSubscriptions extends Command
{
    protected $signature = 'subscriptions:update';
    protected $description = 'Update subscriptions for active users';

    public function handle()
    {
        // DB::table('subscriptions')
        //     ->where('user_id', 2018)
        //     ->update([
        //         'stripe_id' => null,
        //         'trial_ends_at' => null,
        //         'stripe_status' => 'active',
        //         'interval' => 'monthly',
        //         'ends_at' => '2024-12-05 01:05:07',
        //         'created_at' => '2023-03-05 01:05:07'
        //     ]);

        // dd("done");

        $activeUsers = User::whereHas('subscriptions', function ($query) {
            $query->where('stripe_status', 'active');
            $query->where('stripe_update', null);
        })->where('card_brand','!=', null)->get();
        
        // $activeUsers = $activeUsers->take(2);
        dd($activeUsers);
        foreach ($activeUsers as $user) {
            $subscription = $user->subscription('Membership_Subscription');
            $subscription_id = $subscription->id;
            // dd($subscription_id);

            
            if ($subscription && $subscription->active() &&  $subscription->interval != "lifetime") {
                $remainingDays = null;
                $endDate = Carbon::parse($subscription->ends_at);
                $remainingDays = now()->diffInDays($endDate);
                $subscription = $user->newSubscription('Membership_Subscription', $subscription->subscriptionPlan->meta['stripe_price_id'])
                ->trialDays($remainingDays)
                ->add();
                DB::table('subscriptions')
                    ->where('id', $subscription_id)
                    ->update([
                        'stripe_update' => '1',
                        'stripe_status' => 'stale'
                        
            ]);
                $this->info("Updated subscription for user: {$subscription}");
            }elseif($subscription->interval == "lifetime"){
                $freeplan = SubscriptionPlan::where('amount', 0.00)->first();
                // dd($freeplan->meta['stripe_price_id']);
                $subscription = $user->newSubscription('Membership_Subscription', $freeplan->meta['stripe_price_id'])->add();
                DB::table('subscriptions')
                    ->where('id', $subscription_id)
                    ->update([
                        'stripe_update' => '1',
                        'stripe_status' => 'stale'
                        
            ]);
                $this->info("Updated subscription for user: {$subscription}");
            }
        }

        $this->info('Subscription update completed.');
    }
}
