<?php

namespace App\Models\Traits\Relations;

use App\Models\User;

trait LeadRelations
{
    public function affiliate()
    {
        return $this->belongsTo(User::class, 'affiliate_id');
    }

    public function advisor()
    {
        return $this->belongsTo(User::class, 'advisor_id');
    }
}
