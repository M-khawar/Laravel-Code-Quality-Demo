<?php

namespace Database\Seeders;

use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Packages\StripeWrapper\StripeFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $strip = new StripeFactory;

        $data = [
//            ["name" => Subscription::TRAIL_PLAN, "amount" => 1, "meta" => ["stripe_price_id" => $strip->createStripeProductPrice(["product_name" => Subscription::TRAIL_PLAN, "amount" => 1, "interval" => Subscription::PLAN_INTERVAL_DAY, "interval_count" => 7])->id, "interval" => Str::plural("7 " . Subscription::PLAN_INTERVAL_DAY)]],
            [
                "name" => Subscription::MONTHLY_TRAIL_TEXT,
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

        foreach ($data as $d) {
            $this->createPlan($d);
        }
    }

    public function createPlan($planArr)
    {
        $plan = SubscriptionPlan::create($planArr);
    }

}
