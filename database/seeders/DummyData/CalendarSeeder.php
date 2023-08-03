<?php

namespace Database\Seeders\DummyData;

use App\Models\Calendar;
use Carbon\CarbonPeriod;
use Illuminate\Database\Seeder;
use Label84\HoursHelper\Facades\HoursHelper;

class CalendarSeeder extends Seeder
{
    public function run()
    {
        $hours = HoursHelper::create('11:30', '14:30', 60);
        $period = CarbonPeriod::create(now()->startOfYear(), now()->endOfYear());

        $dates = [];
        foreach ($period as $date) {
            $dates[] = $date->format('Y-m-d');
        }


        foreach ($dates as $date) {
            foreach ($hours as $index => $hour) {
                if ($index == count($hours) - 1) {
                    continue; //skipping last iteration
                }

                Calendar::factory()->create([
                    "display_date" => $date,
                    "start_time" => $hour,
                    "end_time" => isset($hours[$index + 1]) ? $hours[$index + 1] : $hour
                ]);

            }
        }
    }
}
