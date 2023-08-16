<?php

namespace App\Models\Traits\Relations;

use App\Models\{CourseLesson, CourseSection};
use Illuminate\Database\Eloquent\Relations\{BelongsToMany, HasMany, HasManyThrough};

trait CourseRelation
{
    public function AllowedAudienceRoles(): BelongsToMany
    {
        $RoleModel = app(config('permission.models.role'));

        return $this->belongsToMany($RoleModel::class, 'has_course_permissions', 'course_id', 'role_id');
    }

    public function sections(): HasMany
    {
        return $this->hasMany(CourseSection::class);
    }

    public function lessons(): HasManyThrough
    {
        return $this->hasManyThrough(CourseLesson::class, CourseSection::class, 'course_id', 'section_id');
    }

}
