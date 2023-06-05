<?php

namespace App\Models\Traits\Relations;

use App\Models\Address;
use App\Models\Profile;

trait UserRelations
{
    public function address()
    {
        return $this->morphOne(Address::class, 'addressable')->withDefault();
    }

    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id');
    }

}
