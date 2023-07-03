<?php

namespace App\Providers;

use App\Contracts\Repositories\OnboardingRepositoryInterface;
use App\Models\Question;
use App\Repositories\OnboardingRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    const bindings = [
        OnboardingRepositoryInterface::class => ["class" => OnboardingRepository::class, "model" => Question::class],
    ];


    public function register()
    {
        foreach (self::bindings as $contract => $bindings) {
            $bindAbleClass = $bindings['class'];
            $model = $bindings['model'] ?? null;

            $this->app->singleton($contract, function () use ($bindAbleClass, $model) {
                $model = app($model);
                return $model ? new $bindAbleClass($model) : new $bindAbleClass();
            });
        }
    }

    public function boot()
    {
        //
    }
}
