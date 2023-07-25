<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class MemberCollection extends ResourceCollection
{
    public static $wrap="collections";

    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
