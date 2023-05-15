<?php

namespace App\Packages\BuilderMacros;

use App\Packages\BuilderMacros\Mixins\JsonResponseMacros;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class BuilderMacrosServiceProvider extends ServiceProvider
{

    public function boot()
    {
        Collection::make(glob(__DIR__ . '/Mixins/*.php'))
            ->mapWithKeys(function ($path) {
                return [$path => pathinfo($path, PATHINFO_FILENAME)];

            })->each(function ($macro, $path) {
                $className = str_replace(['.php', '/', 'app'], ['', '\\', 'App'], $path);
                $className = substr($className, strpos($className, 'App'));

                ['parentClass' => $parentClass, 'mixin' => $mixin] = $className::boot();
                app($parentClass)::mixin(new $mixin);
            });


        Collection::make(glob(__DIR__ . '/Macros/*.php'))
            ->mapWithKeys(function ($path) {
                return [$path => pathinfo($path, PATHINFO_FILENAME)];

            })->each(function ($macro, $path) {
                require_once $path;
            });

    }
}
