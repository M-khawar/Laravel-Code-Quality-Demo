<?php

namespace Database\Seeders;

use App;
use Illuminate\Support\Facades\DB;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Packages\StripeWrapper\StripeFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\Traits\DisableForeignKeys;
use Database\Seeders\Traits\TruncateTable;
use Illuminate\Support\Str;

class SubscriptionPlanSeeder extends Seeder
{
    use DisableForeignKeys, TruncateTable;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('users')
        // ->where('user)id', 2018)
        //     ->update(['stripe_id' => '']);
        //     dd('done');
        $this->disableForeignKeys();
        $this->truncateMultiple(["subscription_plans"]);
        $strip = new StripeFactory;
        if (env('APP_ENV') === 'production') {
            $this->disableForeignKeys();
            $this->truncateMultiple(["subscription_plans"]);
            $data = [
                //            ["name" => Subscription::TRAIL_PLAN, "amount" => 1, "meta" => ["stripe_price_id" => $strip->createStripeProductPrice(["product_name" => Subscription::TRAIL_PLAN, "amount" => 1, "interval" => Subscription::PLAN_INTERVAL_DAY, "interval_count" => 7])->id, "interval" => Str::plural("7 " . Subscription::PLAN_INTERVAL_DAY)]],
                [
                    "name" => Subscription::FREE_PLAN,
                    "amount" => 0,
                    "meta" => [
                        "stripe_price_id" => "price_1OHpVkJUDiGY9EXno2ILFOT2",
                        "interval" => Str::singular(Str::plural("12 " . Subscription::PLAN_INTERVAL_MONTH)) // Lifetime free plan
                    ]
                ],
                [
                    "name" => Subscription::MONTHLY_PLAN,
                    "amount" => 37,
                    "meta" =>
                    [
                        "stripe_price_id" => "price_1HvniPJUDiGY9EXncayUwxJ8",
                        "interval" => Subscription::PLAN_INTERVAL_MONTH
                    ]
                ],
                [
                    "name" => Subscription::ANNUAL_PLAN,
                    "amount" => 297,
                    "meta" => [
                        "stripe_price_id" => "price_1HvniHJUDiGY9EXnbxDmPn3g",
                        "interval" => Subscription::PLAN_INTERVAL_YEAR
                    ]
                ],
            ];
            $this->enableForeignKeys();
        } else {
            $data =         [

                //            ["name" => Subscription::TRAIL_PLAN, "amount" => 1, "meta" => ["stripe_price_id" => $strip->createStripeProductPrice(["product_name" => Subscription::TRAIL_PLAN, "amount" => 1, "interval" => Subscription::PLAN_INTERVAL_DAY, "interval_count" => 7])->id, "interval" => Str::plural("7 " . Subscription::PLAN_INTERVAL_DAY)]],

                [
                    "name" => Subscription::FREE_PLAN,
                    "amount" => 0,
                    "meta" => [
                        "stripe_price_id" => $strip->createStripeProductPrice([
                            "product_name" => Subscription::FREE_PLAN,
                            "amount" => 0,
                            "interval" => Subscription::PLAN_INTERVAL_MONTH,
                            "interval_count" => 12 * 12 // Lifetime free plan
                        ])->id,
                        "interval" => Str::singular(Str::plural("12 " . Subscription::PLAN_INTERVAL_MONTH)) // Lifetime free plan
                    ]
                ],
                [
                    "name" => Subscription::MONTHLY_PLAN,
                    "amount" => 37,
                    "meta" =>
                    [
                        "stripe_price_id" => $strip->createStripeProductPrice(["product_name" => Subscription::MONTHLY_PLAN, "amount" => 37, "interval" => Subscription::PLAN_INTERVAL_MONTH])->id,
                        "interval" => Subscription::PLAN_INTERVAL_MONTH
                    ]
                ],
                [
                    "name" => Subscription::ANNUAL_PLAN,
                    "amount" => 297,
                    "meta" => [
                        "stripe_price_id" => $strip->createStripeProductPrice(["product_name" => Subscription::ANNUAL_PLAN, "amount" => 297, "interval" => Subscription::PLAN_INTERVAL_YEAR])->id,
                        "interval" => Subscription::PLAN_INTERVAL_YEAR
                    ]
                ],
            ];
        }


        foreach ($data as $d) {
            $this->createPlan($d);
        }
        $this->enableForeignKeys();
    }

    public function createPlan($planArr)
    {
        $plan = SubscriptionPlan::create($planArr);
    }
}
