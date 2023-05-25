<?php

namespace App\Models\Traits\Relations;

use App\Models\Address;

trait UserRelations
{
    public function address()
    {
        return $this->morphOne(Address::class, 'addressable')->withDefault();
    }

}
