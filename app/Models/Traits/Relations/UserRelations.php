<?php

namespace App\Models\Traits\Relations;

use App\Models\{Address, Profile, User};

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

    public function advisor()
    {
        return $this->belongsTo(User::class, 'advisor_id');
    }

}
