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
        //     ->where('user_id', 4)
        //     ->update([
        //         'stripe_update' => '1'
        //     ]);

        // dd("done");

        $activeUsers = User::whereHas('subscriptions', function ($query) {
            $query->where('stripe_status', 'active');
            $query->where('stripe_update', null);
            $query->where('stripe_id', null);
            $query->where('interval', 'lifetime');
        })->where('paypal_id', null)->where('stripe_id','!=', null)->get();
        
        // $activeUsers = $activeUsers->take(1);
        // dd(count($activeUsers));
        foreach ($activeUsers as $user) {
            $subscription = $user->subscription('Membership_Subscription');
            $subscription_id = $subscription->id;
            // echo $subscription_id . "<br>"; 
            // dd($subscription_id);
            
            // $user = DB::table('subscriptions')
            // ->where('interval' ,'lifetime')
            // ->where('interval','!=' ,null)
            // ->first();
            // if($$subscription_id = 14){ // 221
            //     continue;
            //     // echo $subscription_id."<br>";
            //     // dd($subscription_id);
            // }
            if ($subscription && isset($subscription) && $subscription->active() &&  $subscription->interval != "lifetime") {
                $remainingDays = null;
                $endDate = Carbon::parse($subscription->ends_at);
                $remainingDays = now()->diffInDays($endDate);
                dd($remainingDays);
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
                $freePriceId = 'price_1OHpVkJUDiGY9EXno2ILFOT2';
                $subscription = $user->newSubscription('Membership_Subscription', $freePriceId)->add();
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
