<?php

namespace App\Models;

use BinaryCabin\LaravelUUID\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory, HasUUID;

    protected $fillable = [
        'user_id', 'display_name', 'display_text', 'head_code', 'body_code',
    ];
}
