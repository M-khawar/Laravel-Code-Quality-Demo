<?php

namespace App\Models;

use App\Models\Traits\Relations\CourseLessonRelations;
use BinaryCabin\LaravelUUID\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseLesson extends Model
{
    use HasFactory, HasUUID, SoftDeletes, CourseLessonRelations;

    protected $fillable = [
        "section_id", "video_id", "name", "description", "resources", "position",
    ];
}
