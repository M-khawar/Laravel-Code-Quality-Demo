<?php

namespace App\Providers;

use App\Contracts\Repositories\{AdminCourseRepositoryInterface,
    CalendarRepositoryInterface,
    CourseRepositoryInterface,
    LeadRepositoryInterface,
    OnboardingRepositoryInterface,
    ProfileRepositoryInterface,
    PromoteRepositoryInterface,
    UserRepositoryInterface
};
use App\Models\{Calendar, Course, Lead, Question, User};
use App\Repositories\{AdminCourseRepository,
    CalendarRepository,
    CourseRepository,
    LeadRepository,
    OnboardingRepository,
    ProfileRepository,
    PromoteRepository,
    UserRepository
};
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    const bindings = [
        OnboardingRepositoryInterface::class => ["class" => OnboardingRepository::class, "model" => Question::class],
        UserRepositoryInterface::class => ["class" => UserRepository::class, "model" => User::class],
        PromoteRepositoryInterface::class => ["class" => PromoteRepository::class, "model" => null],
        LeadRepositoryInterface::class => ["class" => LeadRepository::class, "model" => Lead::class],
        CalendarRepositoryInterface::class => ["class" => CalendarRepository::class, "model" => Calendar::class],
        CourseRepositoryInterface::class => ["class" => CourseRepository::class, "model" => Course::class],
        AdminCourseRepositoryInterface::class => ["class" => AdminCourseRepository::class, "model" => Course::class],
        ProfileRepositoryInterface::class => ["class" => ProfileRepository::class, "model" => User::class],
    ];


    public function register()
    {
        foreach (self::bindings as $contract => $bindings) {
            $bindAbleClass = $bindings['class'];
            $model = $bindings['model'] ?? null;

            $this->app->singleton($contract, function () use ($bindAbleClass, $model) {
                if ($model) $model = app($model);
                return $model ? new $bindAbleClass($model) : new $bindAbleClass();
            });
        }
    }

    public function boot()
    {
        //
    }
}
