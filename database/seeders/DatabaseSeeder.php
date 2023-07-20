<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Seeders\DummyData\DummyDatabaseSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(VideoSeeder::class);
        $this->call(QuestionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(UserBadgeSeeder::class);
        $this->call(AdminSeeder::class);

        if (app()->environment('local', 'staging')) {
            $this->call(DummyDatabaseSeeder::class);
        }
    }
}
