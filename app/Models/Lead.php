<?php

namespace App\Models;

use App\Models\Traits\Relations\LeadRelations;
use BinaryCabin\LaravelUUID\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory, HasUUID, LeadRelations;

    protected $fillable = ['name', 'email', 'instagram', 'advisor_id', 'affiliate_id', 'status'];
}
