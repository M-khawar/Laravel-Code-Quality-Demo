<?php

namespace App\Models\Traits\Relations;

trait AddressRelations
{

    public function addressable()
    {
        return $this->morphTo();
    }
}
