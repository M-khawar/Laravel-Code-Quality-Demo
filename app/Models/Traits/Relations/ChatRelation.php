<?php

namespace App\Models\Traits\Relations;

use App\Models\User;

trait ChatRelation
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
