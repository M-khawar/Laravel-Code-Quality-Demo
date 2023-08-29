<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class MemberCollection extends ResourceCollection
{
    public static $wrap = 'collection';

    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
