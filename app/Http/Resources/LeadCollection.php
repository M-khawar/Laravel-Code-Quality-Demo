<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class LeadCollection extends ResourceCollection
{
    public static $wrap = 'collection';

    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
