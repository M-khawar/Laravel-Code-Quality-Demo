<?php

namespace App\Models;

use App\Models\Traits\Relations\CourseRelation;
use BinaryCabin\LaravelUUID\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory, HasUUID, CourseRelation;

    protected $fillable = ["name", "description", "thumbnail"];
}
