<?php

namespace App\Providers;

use App\Contracts\Repositories\{OnboardingRepositoryInterface, UserRepositoryInterface};
use App\Models\{Question, User};
use App\Repositories\{OnboardingRepository, UserRepository};
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    const bindings = [
        OnboardingRepositoryInterface::class => ["class" => OnboardingRepository::class, "model" => Question::class],
        UserRepositoryInterface::class => ["class" => UserRepository::class, "model" => User::class],
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
