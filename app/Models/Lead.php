<?php

namespace App\Models;

use App\Models\Traits\Globals\Searchable;
use App\Models\Traits\Relations\LeadRelations;
use BinaryCabin\LaravelUUID\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use HasFactory, HasUUID, LeadRelations, SoftDeletes, Searchable;

    protected $fillable = ['name', 'email', 'instagram', 'advisor_id', 'affiliate_id', 'funnel_type', 'status'];

    protected $searchable_columns = ['name', 'email', 'instagram'];

    public function scopeFindByEmail($query, $email)
    {
        return $query->where('email', $email);
    }
}
