<?php

namespace App\Packages\BuilderMacros\Mixins;

class MixinsAbstract
{
    protected static $parentClass;

    public static function boot(): array
    {
        return [
            'parentClass' => static::$parentClass, //static will get child_called class
            'mixin' => get_called_class(),
        ];
    }
}
