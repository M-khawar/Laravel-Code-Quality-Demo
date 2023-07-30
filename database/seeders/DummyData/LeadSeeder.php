<?php

namespace Database\Seeders\DummyData;

use App\Models\Lead;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LeadSeeder extends Seeder
{
    public function run()
    {
        Lead::factory()->count(100)->create([
            'affiliate_id' => 2
        ]);

    }
}
