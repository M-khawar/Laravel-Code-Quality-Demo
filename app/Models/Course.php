<?php

namespace App\Models;

use App\Models\Traits\Relations\CourseRelation;
use BinaryCabin\LaravelUUID\Traits\HasUUID;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, HasUUID, CourseRelation, SoftDeletes;

    protected $fillable = ["name", "description", "thumbnail"];

    protected $appends = ["thumbnail_path"];


    public static function findOrFailCourseByUuid(string $uuid)
    {
        return static::byUUID($uuid)->firstOrFail();
    }

    protected function thumbnailPath(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                return $attributes['thumbnail_path'] = $attributes['thumbnail'] ?? asset('assets/images/no_image_available.jpg');
            }
        );
    }
}
