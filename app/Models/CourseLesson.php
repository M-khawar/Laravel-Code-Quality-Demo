<?php

namespace App\Models;

use App\Models\Traits\Relations\CourseLessonRelations;
use BinaryCabin\LaravelUUID\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Rutorika\Sortable\SortableTrait;

class CourseLesson extends Model
{
    use HasFactory, HasUUID, SoftDeletes, CourseLessonRelations, SortableTrait;

    protected $fillable = [
        "section_id", "video_id", "name", "description", "resources", "position",
    ];

    protected static $sortableGroupField = ['section_id'];

    public static function findOrFailLessonByUuid(string $uuid)
    {
        return static::byUUID($uuid)->firstOrFail();
    }
}
