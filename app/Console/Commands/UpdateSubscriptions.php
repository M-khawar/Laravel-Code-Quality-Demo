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
        //     ->where('user_id', 2020)
        //     ->update([
        //         'stripe_id' => null,
        //         'trial_ends_at' => null,
        //         'stripe_status' => 'active',
        //         'interval' => 'annual',
        //         'ends_at' => '2024-12-05 01:05:07',
        //         'created_at' => '2023-03-05 01:05:07'
        //     ]);

        // dd("done");

        $activeUsers = User::whereHas('subscriptions', function ($query) {
            $query->where('stripe_status', 'active');
            $query->where('user_id', '2020');
            $query->where('stripe_update', null);
        })->where('paypal_id', null)->get();
        
        // $activeUsers = $activeUsers->take(2);
        dd($activeUsers);
        foreach ($activeUsers as $user) {
            $subscription = $user->subscription('Membership_Subscription');
            $subscription_id = $subscription->id;
            // dd($subscription_id);

            
            if ($subscription && isset($subscription) && $subscription->active() &&  $subscription->interval != "lifetime") {
                $remainingDays = null;
                $endDate = Carbon::parse($subscription->ends_at);
                $remainingDays = now()->diffInDays($endDate);
                $annualPriceId = 'price_1HvniHJUDiGY9EXnbxDmPn3g';
                $monthPriceId = 'price_1HvniPJUDiGY9EXncayUwxJ8';
                if(isset($subscription->subscriptionPlan->meta['stripe_price_id']) && $remainingDays > 0){
                    if($subscription->interval == "monthly"){
                        // dd($subscription->subscriptionPlan->meta['stripe_price_id']);
                        $subscription = $user->newSubscription('Membership_Subscription', $monthPriceId)
                                                ->trialDays($remainingDays)
                                                ->add();
                    }elseif(($subscription->interval == "annual")){
                        $subscription = $user->newSubscription('Membership_Subscription', $annualPriceId)
                                                ->trialDays($remainingDays)
                                                ->add();
                    }
                    
                    DB::table('subscriptions')
                        ->where('id', $subscription_id)
                        ->update([
                            'stripe_update' => '1',
                            'stripe_status' => 'stale'
                ]);
                    $this->info("Updated subscription for user: {$subscription}");
                }
                
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
