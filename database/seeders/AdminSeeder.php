<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\Traits\DisableForeignKeys;
use Database\Seeders\Traits\TruncateTable;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = [
            'name' => 'Colten Echave',
            'email' => 'workwithcolten@gmail.com',
            'password' => Hash::make('password'),
            'instagram' => 'colten.echave',
            'phone' => '+1-435-223-4483',
            'affiliate_code' => 'colten95ohhin6',
            'advisor_id' => 1,
            'is_admin' => true,
            'is_advisor' => true,
            'advisor_date' => '2023-06-08',
            'is_active_recruiter' => true,
        ];

        $adminProfile = [
            'display_name' => 'Colten Echave',
            'display_text' => null,
            'head_code' => null,
            'body_code' => null,
            'is_enagic' => true,
            'questionnaire_completed' => true,
            'is_trifecta' => true,
            'is_core' => true,
            'enagic_data' => '2023-06-08',
            'trifecta_date' => '2023-06-08',
            'core_date' => '2023-06-08',
            'lead_email' => false,
            'lead_sms' => true,
            'mem_email' => false,
            'mem_sms' => true,
            'promote_watched' => true,
            'welcome_video' => true,
            'fb_group' => true,
        ];

        User::create($admin)->profile()->create($adminProfile);
    }
}
