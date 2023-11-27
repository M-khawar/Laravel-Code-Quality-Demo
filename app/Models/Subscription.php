<?php

namespace App\Models;

use BinaryCabin\LaravelUUID\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Cashier\Subscription as CashierSubscription;

class Subscription extends CashierSubscription
{
    use HasFactory, HasUUID;

    const MONTHLY_PLAN = "Race To Freedom Monthly Membership";
    const ANNUAL_PLAN = "Race To Freedom Annual Membership";
    const MONTHLY_TRAIL_TEXT = "$1 Trial for 7 days then $37/Month";

    const PLAN_INTERVAL_MONTH = "month";
    const PLAN_INTERVAL_YEAR = "year";

    protected $with = ['subscriptionPlan'];

    public function subscriptionPlan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id', 'id')->withDefault();
    }
}
