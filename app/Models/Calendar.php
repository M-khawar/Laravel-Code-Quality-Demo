<?php

namespace App\Models;

use App\Models\Traits\Relations\CalendarRelation;
use BinaryCabin\LaravelUUID\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{
    use HasFactory, HasUUID, CalendarRelation;

    protected $fillable = ["title", "description", "link", "calendar_timestamp", "display_date", "start_time", "end_time"];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($item) {
            $item->calendar_timestamp = $item->display_date . " " . $item->start_time;
        });
    }


}
