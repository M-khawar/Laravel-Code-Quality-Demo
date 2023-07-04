<?php

namespace App\Models;

use BinaryCabin\LaravelUUID\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory,HasUUID;

    protected $fillable = ['slug', 'link', 'source'];

    public static function findBySlug($slug)
    {
        return self::where('slug', $slug)->first();
    }
}
