<?php

namespace App\Models;

use App\Models\Traits\Relations\CourseSectionRelations;
use BinaryCabin\LaravelUUID\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseSection extends Model
{
    use HasFactory, HasUUID, SoftDeletes, CourseSectionRelations;

    protected $fillable = ["course_id", "name", "description", "position"];
}
