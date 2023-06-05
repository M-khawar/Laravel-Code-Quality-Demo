<?php

namespace App\Models\Traits\Globals;

trait AffiliateCodeGenerator
{
    public static function bootAffiliateCodeGenerator()
    {
        static::creating(function ($model) {
            $name = str_replace(" ", "_", substr($model->name, 0, 9));
            $affiliateCode = $name . "_" . substr(md5(mt_rand()), 0, 8) . "_" . substr(md5(mt_rand()), 0, 8);
            $model->affiliate_code = $affiliateCode;
        });
    }
}
