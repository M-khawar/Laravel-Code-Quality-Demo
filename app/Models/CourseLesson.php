<?php

namespace App\Models;

use App\Models\Traits\Relations\CourseLessonRelations;
use BinaryCabin\LaravelUUID\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseLesson extends Model
{
    use HasFactory, HasUUID, CourseLessonRelations;

    protected $fillable = [
        "section_id", "video_id", "name", "description", "resources", "position",
    ];
}
