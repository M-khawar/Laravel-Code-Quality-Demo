<?php

namespace Database\Seeders;

use App\Models\UserBadge;
use App\Traits\CommonServices;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserBadgeSeeder extends Seeder
{
    use CommonServices;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $badges = array(
            ['title' => 'Enagic'],
            ['title' => 'Trifecta'],
            ['title' => 'Advisor'],
            ['title' => 'Core'],
            ['title' => 'Active-Recruiter'],
        );

        $excludedUuid = [];
        foreach ($badges as &$badge) {
            $badge['uuid'] = $this->generateUniqueUUID(UserBadge::class, excludedUuids: $excludedUuid);
            array_push($excludedUuid, $badge['uuid']);
        }

        UserBadge::insert($badges);
    }
}
