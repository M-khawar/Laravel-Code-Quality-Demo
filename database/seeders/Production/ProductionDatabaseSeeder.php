<?php

namespace Database\Seeders\Production;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductionDatabaseSeeder extends Seeder
{
    public function run()
    {
        // $this->call(UserSeeder::class);
        // $this->call(FunnelSeeder::class);
        $this->call(CourseSeeder::class);
        // $this->call(PageViewsSeeder::class);
        
    }
}
