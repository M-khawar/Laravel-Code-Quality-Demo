<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateAffiliatesAdvisorJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $advisor;
    private $parentAdvisorId;


    public function __construct($advisor)
    {
        $this->advisor = $advisor;
        $this->parentAdvisorId = $advisor->advisor_id;
    }


    public function handle()
    {
        $affiliates = $this->buildNestedAffiliates($this->advisor->id, $this->parentAdvisorId);
        $mutationAffiliatesID = $affiliates->pluck("id")->toArray();
        $this->assignNewAdvisorToAffiliates($mutationAffiliatesID);
    }

    private function buildNestedAffiliates($affiliateID, $parentAdvisorID)
    {
        $affiliatedMembers = $this->fetchAffiliatedMembers($affiliateID, $parentAdvisorID);

        if (count($affiliatedMembers) < 1) {
            return $affiliatedMembers;
        }

        $nested = collect([]);
        $affiliatedMembers->each(function ($user) use ($parentAdvisorID, &$nested) {
            $data = $this->buildNestedAffiliates($user->id, $user->advisor_id);
            if (count($data) > 0) $nested = $nested->concat($data);
        });

        return $affiliatedMembers->merge($nested);
    }

    private function fetchAffiliatedMembers($affiliateID, $parentAdvisorID)
    {
        return User::getAffiliatedMembers($affiliateID, function ($q) use ($parentAdvisorID) {

            $q->whereDoesntHave("roles", fn($q) => $q->where("roles.name", ADVISOR_ROLE))
                ->where("advisor_id", $parentAdvisorID)
                ->orderBy("id");
        });
    }

    private function assignNewAdvisorToAffiliates(array $mutationAffiliatesID)
    {
        return User::whereIn("id", $mutationAffiliatesID)->update(["advisor_id" => $this->advisor->id]);
    }
}
