<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UserSettingConfigurationListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle(Registered $event)
    {
        $user = $event->user;

        $defaultSettings = config("settings.default");

        foreach ($defaultSettings as $key => $value) {
            [$group, $property] = $user->splitKey($key);
            $user->updateOrInsertProperty($group, $property, $value);
        }
    }
}
