<?php

namespace App\Models;

use App\Models\Traits\Relations\CalendarNotificationRelation;
use BinaryCabin\LaravelUUID\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarNotification extends Model
{
    use HasFactory, HasUUID, CalendarNotificationRelation;

    protected $fillable = ["user_id", "calendar_id", "type", "during", "during_type", "sent_status"];


}
