<?php

namespace App\Models\Traits\Relations;

use App\Models\CalendarNotification;
use Illuminate\Database\Eloquent\Relations\{BelongsToMany, HasMany};

trait CalendarRelation
{
    public function AllowedAudienceRoles(): BelongsToMany
    {
        $RoleModel = app(config('permission.models.role'));

        return $this->belongsToMany($RoleModel::class, 'has_calendar_permissions', 'calender_id', 'role_id');
    }

    public function calendarNotifications(): HasMany
    {
        return $this->hasMany(CalendarNotification::class, 'calendar_id');
    }
}
