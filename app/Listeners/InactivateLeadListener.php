<?php

namespace App\Listeners;

use App\Models\Lead;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class InactivateLeadListener
{

    public function __construct()
    {
        //
    }


    public function handle(Registered $event)
    {
        $user = $event->user;
        $lead = Lead::findByEmail($user->email)->first();

        if (!$lead) {
            return false;
        }

        $lead->status = LEAD_IN_ACTIVE;
        $lead->saveQuietly();
    }
}
