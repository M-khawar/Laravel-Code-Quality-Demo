<?php

namespace App\Providers;

use App\Models\{Address, Subscription, User};
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Cashier;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Cashier::useSubscriptionModel(Subscription::class);
        AnonymousResourceCollection::wrap("collection");

        Relation::enforceMorphMap([
            'User' => User::class,
            'Address' => Address::class,
        ]);
    }
}
