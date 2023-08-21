<?php

namespace App\Models;

use App\Models\Traits\Relations\CourseSectionRelations;
use BinaryCabin\LaravelUUID\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Rutorika\Sortable\SortableTrait;

class CourseSection extends Model
{
    use HasFactory, HasUUID, SoftDeletes, CourseSectionRelations, SortableTrait;

    protected $fillable = ["course_id", "name", "description", "position"];

    protected static $sortableGroupField = ['course_id'];

    public static function findOrFailSectionByUuid(string $uuid)
    {
        return static::byUUID($uuid)->firstOrFail();
    }
}
