<?php

namespace App\Models;

use App\Models\Traits\Relations\AddressRelations;
use BinaryCabin\LaravelUUID\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory, HasUUID, AddressRelations;

    protected $fillable = ['city', 'state', 'zipcode', 'address',];

}
