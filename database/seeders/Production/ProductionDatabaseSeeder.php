<?php

namespace Database\Seeders\Production;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductionDatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(FunnelSeeder::class);
        $this->call(CourseSeeder::class);
        $this->call(CompleteCourseSeeder::class);
        $this->call(CalendarSeeder::class);
        $this->call(QuestionAndAnswersSeeder::class);
        $this->call(NotesSeeder::class);
        $this->call(LeadsSeeder::class);
        $this->call(AdvisorSettingsSeeder::class);
        $this->call(SubscriptionSeeder::class);
        $this->call(PageViewsSeeder::class);
        
    }
}
