<?php

namespace Database\Seeders;

use App\Models\UserBadge;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserBadgeSeeder extends Seeder
{
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

        UserBadge::insert($badges);
    }
}
