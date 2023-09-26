<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class ConfigureActiveRecruiterRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'configure:activeRecruiterRole';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Configure Active Recruiter Role';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $this->revokeActiveRecruiterRole();
        $users = $this->recentlySubscribedUser();
        $this->assignActiveRecruiterRole($users);

        return Command::SUCCESS;
    }

    private function assignActiveRecruiterRole($users): Collection
    {
        return $users->each->assignRole(ACTIVE_RECRUITER_ROLE);
    }

    private function revokeActiveRecruiterRole(): void
    {
        $usersActiveRecruiterRole = User::whereHas("roles", fn($q) => $q->where("name", ACTIVE_RECRUITER_ROLE))->get();
        $usersActiveRecruiterRole->each->removeRole(ACTIVE_RECRUITER_ROLE);
    }

    private function recentlySubscribedUser()
    {
        $dateRange = $this->thirtyDayRange();
        $subscriptions = Subscription::query()
            ->whereBetween("created_at", array($dateRange->start_date, $dateRange->end_date))
            ->where(fn($q) => $q->active())
            ->orWhere(fn($q) => $q->onTrial())
            ->with("owner")
            ->get();

        $affiliatesID = $subscriptions->map->owner->pluck("affiliate_id")->toArray();
        $affiliatesID = array_unique($affiliatesID);  //removing duplicate affiliates IDs

        return User::whereIn("id", $affiliatesID)->excludeAdmins()->get();
    }

    private function thirtyDayRange(): object
    {
        $start_date = now()->subDay(30)->startOfDay()->toDateTimeString();
        $end_date = now()->endOfDay()->toDateTimeString();

        return (object)compact('start_date', 'end_date');
    }
}
