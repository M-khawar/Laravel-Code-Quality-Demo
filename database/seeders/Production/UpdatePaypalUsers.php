<?php

namespace Database\Seeders\Production;

use App\Models\User;
use Database\Seeders\Traits\DisableForeignKeys;
use Database\Seeders\Traits\TruncateTable;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdatePaypalUsers extends ConfigureDatabase
{
    use DisableForeignKeys, TruncateTable;

    public function run()
{
    // Schema::table('users', function (Blueprint $table) {
    //     $table->string('paypal_id')->nullable()->after('stripe_id');
    // });
//  
        $this->disableForeignKeys();
        // DB::table('users')
        // ->update([
        //     'paypal_id' => DB::raw("CASE WHEN stripe_id LIKE 'Paypal:%' THEN stripe_id ELSE NULL END"),
        //     'stripe_id' => DB::raw("CASE WHEN stripe_id LIKE 'Paypal:%' THEN NULL ELSE stripe_id END"),
        // ]);
    
        // dd('yes');
        $subscriptions = DB::table('users')
        ->join('subscriptions', 'users.id', '=', 'subscriptions.user_id')
        ->where('users.stripe_id', 'like', 'Paypal:%')
        ->select('users.stripe_id', 'subscriptions.id','subscriptions.updated_at','subscriptions.user_id','subscriptions.name', 'subscriptions.subscription_plan_id', 'subscriptions.stripe_status', 'subscriptions.interval', 'subscriptions.ends_at', 'subscriptions.created_at', 'subscriptions.name')
        ->get();
        // dd($subscriptions);
        $rawsubscriptions = $subscriptions->map(function ($subscription) {
            return $this->buildPaypalsubscription($subscription);
        });
    //    dump($rawsubscriptions);die;
        collect($rawsubscriptions)->each(function ($subscription) {
            $this->storePaypalSubscription($subscription);
        });
    


    $this->enableForeignKeys();
}
private function storePaypalSubscription($subscription)
{   
    $paypalSubscriptionId = DB::table('paypalsubscriptions')->insertGetId([
        'user_id' => $subscription['user_id'],
        'subscription_plan_id' => $subscription['subscription_plan_id'] ?? null,
        'stripe_status' => $subscription['stripe_status'],
        'created_at' => $subscription['created_at'],
        'updated_at' => $subscription['updated_at'],
        'interval' => $subscription['interval'],
        'ends_at' => $subscription['ends_at']
    ]);

    DB::table('subscriptions')->where('id', $subscription['id'])->delete();
}

private function buildPaypalsubscription($subscription)
{
    return [
        'id'  => $subscription->id,
        'user_id' => $subscription->user_id ?? null,
        'subscription_plan_id' => $subscription->subscription_plan_id,
        'stripe_status' => $subscription->stripe_status,
        'created_at' => $subscription->created_at,
        'updated_at' => $subscription->updated_at,
        'interval'   =>  $subscription->interval,
        'ends_at' => $subscription->ends_at
    ];
}


}