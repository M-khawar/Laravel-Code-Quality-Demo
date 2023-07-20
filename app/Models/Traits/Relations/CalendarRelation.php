<?php

namespace App\Models\Traits\Relations;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait CalendarRelation
{
    public function AllowedAudienceRoles(): BelongsToMany
    {
        $RoleModel = app(config('permission.models.role'));

        return $this->belongsToMany($RoleModel::class, 'has_calendar_permissions', 'calender_id', 'role_id');
    }
}
