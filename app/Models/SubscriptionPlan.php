<?php

namespace App\Models;

use BinaryCabin\LaravelUUID\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory, HasUUID;

    protected $fillable = ['name', 'amount', 'meta'];

    protected $casts = [
        'meta' => 'json',
    ];

    /**
     * higher order message from laravel docs
     */
    public function mapPrice()
    {
        if ($this->meta["interval"] == Subscription::PLAN_INTERVAL_MONTH) {
            return $this->amount_text = Subscription::MONTHLY_TRAIL_TEXT;
        }

        return $this->amount_text = "$" . (double)$this->amount;
    }
}
