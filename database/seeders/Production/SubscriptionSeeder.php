<?php

namespace Database\Seeders\Production;

use Illuminate\Support\Facades\DB;
use App\Models\subscription;
use App\Models\User;
use Database\Seeders\Traits\DisableForeignKeys;
use Database\Seeders\Traits\TruncateTable;

class SubscriptionSeeder extends ConfigureDatabase
{
    // private $roleModel;
    use DisableForeignKeys, TruncateTable;

    public function run()
    {
        $this->disableForeignKeys();
        $this->truncateMultiple(["subscriptions","subscription_items"]);

        $subscriptions = $this->getConnection()
                        ->table("subscriptions")
                        ->selectRaw("*")
                        ->get();

    //    $subscriptions = $subscriptions->take(10);
        // dump($subscriptions);exit;
        $rawsubscriptions = $subscriptions->map(function ($subscription) {
            return $this->buildsubscription($subscription);
        });
    //    dump($rawsubscriptions);die;
        collect($rawsubscriptions)->each(function ($subscription) {
            $this->storeSubscription($subscription);
        });

        $this->enableForeignKeys();
    }

    private function storeSubscriptionbkup(array $subscriptionData)
    {
        $userStripeData = $subscriptionData['userStripeData'];
        $transactionStripe = $subscriptionData['transactionStripe'];
        unset($subscriptionData['userStripeData']);
        unset($subscriptionData['transactionStripe']);

        subscription::create([...$subscriptionData]);
    }
    private function storeSubscription(array $subscriptionData)
    {
        $userStripeData = $subscriptionData['userStripeData'];
        $transactionStripe = $subscriptionData['transactionStripe'];
        unset($subscriptionData['userStripeData']);
        unset($subscriptionData['transactionStripe']);
        if($subscriptionData['user_id']){
            $user = User::updateOrCreate(['id' => $subscriptionData['user_id']], $userStripeData);
    
            $subscription = $user->subscriptions()->create($subscriptionData);
        
            DB::table('subscription_items')->insert($transactionStripe + ['subscription_id' => $subscription->id]);
        
        }
        return;
       }
  
    private function buildsubscription($subscription)
    {
        $timestamp = (int)$subscription->created_at;
        $timestamp = intval($timestamp / 1000);
        $created_at = $this->timeStampConversion($timestamp);
        $userData =  $this->getUsersData($subscription); 
        $subscriptionPlanId = $subscription->plan_id;

        $namePlan = ($subscriptionPlanId == 1) ? 'Race To Freedom Monthly Membership' : (($subscriptionPlanId == 2) ? 'Race To Freedom Annual Membership' : null);
        $stripe_product = ($subscriptionPlanId == 1) ? 'prod_IWrRgYYddI753r' : (($subscriptionPlanId == 2) ? 'prod_IWrRc2ndsVOdwg' : null);
        if($namePlan != null){
            $planprice = DB::select('SELECT * FROM subscription_plans WHERE name = :name', ['name' => $namePlan]);            
            $meta = json_decode($planprice[0]->meta);
            $stripePriceID = $meta->stripe_price_id;
        }
 
        // $stripePriceID = $subscription->plan_id = 2 ? '' ; 
        return [
            // 'id'   => $subscription->id,
            'user_id' => $userData['user_id']->id ?? null,
            'name' => 'Membership_Subscription',
            'quantity' => 1,
            'subscription_plan_id' => $subscription->plan_id,
            'stripe_price' => $stripePriceID ?? null,
            'stripe_status' => $subscription->status,
            'created_at' => $created_at,
            'updated_at' => $created_at,
            'userStripeData'  => [
                'stripe_id' => $userData['data']->stripe_id ?? null,
                'pm_type' => $userData['data']->card_brand ?? null,
                'pm_last_four' => $userData['data']->card_last4 ?? null,
            ],
            'transactionStripe'  => [
                'quantity' => 1,
                'stripe_price' => $stripePriceID ?? null,
                'stripe_product' => $stripe_product,
                'created_at' => $created_at,
                'updated_at' => $created_at,
                ]
        ];
    }
    private function getUsersData($subscription)  {
        $userEmail = $this->getConnection()
                        ->table("users")
                        ->distinct("email")
                        ->where('id',$subscription->user_id)
                        ->selectRaw("*")->first();
        if(isset($userEmail)){
            $userN = DB::select('SELECT id,email FROM users WHERE email = :email', ['email' => $userEmail->email]);

            if ($userN) {
                $userData['user_id'] = $userN[0];
                $userData['data'] = $userEmail;
               return  $userData;
            } 
        }
       
        return null;
    }
}
