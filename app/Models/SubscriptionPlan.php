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
}
