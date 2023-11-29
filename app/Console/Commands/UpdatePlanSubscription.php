<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Laravel\Cashier\Cashier;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class UpdatePlanSubscription extends Command
{
    protected $signature = 'plan:update';
    protected $description = 'Update plan subscriptions for active users';

    public function handle()
    {
        
    }
}
