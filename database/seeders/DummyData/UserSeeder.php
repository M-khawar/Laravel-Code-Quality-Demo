<?php

namespace Database\Seeders\DummyData;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        $advisor = [
            'name' => 'Developer',
            'email' => 'developer@r2f.com',
            'password' => Hash::make('password'),
            'instagram' => null,
            'phone' => fake()->phoneNumber(),
//            'affiliate_code' => 'Developer_1122',
            'advisor_id' => config('default_settings.default_advisor'),
            'affiliate_id' => config('default_settings.default_advisor'),
            'is_advisor' => true,
            'advisor_date' => '2023-06-08',
        ];

        $advisorProfile = [
            'display_name' => 'Developer ilsa',
            'display_text' => __('messages.default_display_text', locale: 'en'),
            'is_enagic' => true,
        ];

        $user = User::create($advisor);
        $user->profile()->create($advisorProfile);
        event(new Registered($user));


        User::factory()->count(100)->create([
            'affiliate_id' => $user->id
        ]);
    }
}
