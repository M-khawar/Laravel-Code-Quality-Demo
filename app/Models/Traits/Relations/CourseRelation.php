<?php

namespace App\Models\Traits\Relations;

use App\Models\{CourseSection};
use Illuminate\Database\Eloquent\Relations\{BelongsToMany, HasMany};

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

}
