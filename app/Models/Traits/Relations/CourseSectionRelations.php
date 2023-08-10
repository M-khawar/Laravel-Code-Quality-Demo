<?php

namespace App\Models\Traits\Relations;

use App\Models\CourseLesson;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait CourseSectionRelations
{

    public function lessons(): HasMany
    {
        return $this->hasMany(CourseLesson::class, 'section_id');
    }
}
