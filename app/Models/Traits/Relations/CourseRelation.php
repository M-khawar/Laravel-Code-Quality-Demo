<?php

namespace App\Models\Traits\Relations;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait CourseRelation
{
    public function AllowedAudienceRoles(): BelongsToMany
    {
        $RoleModel = app(config('permission.models.role'));

        return $this->belongsToMany($RoleModel::class, 'has_course_permissions', 'course_id', 'role_id');
    }

}
