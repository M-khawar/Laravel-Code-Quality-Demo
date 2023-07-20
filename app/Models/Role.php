<?php

namespace App\Models;

use BinaryCabin\LaravelUUID\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use HasFactory, HasUUID;

    public function scopeExcludeAdminRole($query)
    {
        return $query->where('name', '!=', ADMIN_ROLE);
    }

    public function scopeWhereUuidIn($query, array $uuids)
    {
        return $query->whereIn('uuid', $uuids);
    }

}
