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
            'funnel_type' => MASTER_FUNNEL,
            'advisor_id' => config('default_settings.default_advisor'),
            'affiliate_id' => config('default_settings.default_advisor'),
        ];

        $advisorProfile = [
            'display_name' => 'Developer ilsa',
            'display_text' => __('messages.default_display_text', locale: 'en'),
        ];

        $user = User::create($advisor);
        $user->profile()->create($advisorProfile);
        $user->assignRole(ALL_MEMBER_ROLE, ENAGIC_ROLE, TRIFECTA_ROLE, ADVISOR_ROLE);

        event(new Registered($user));


        User::factory()->count(100)->create([
            'affiliate_id' => $user->id
        ]);
    }
}
