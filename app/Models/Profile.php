<?php

namespace App\Models;

use BinaryCabin\LaravelUUID\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory, HasUUID;

    protected $fillable = [
        'user_id', 'display_name', 'display_text', 'head_code', 'body_code', 'is_enagic', 'is_trifecta', 'is_core',
        'enagic_data', 'trifecta_date', 'core_date', 'lead_sms', 'mem_sms', 'promote_watched', 'welcome_video',
        'fb_group',
    ];
}
