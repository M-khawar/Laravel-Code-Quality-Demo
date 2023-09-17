<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
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
            'advisor_id' => config('default_settings.default_advisor'),
            'affiliate_id' => config('default_settings.default_advisor'),
        ];

        $adminProfile = [
            'display_name' => 'Colten Echave',
            'display_text' => __('messages.default_display_text', locale: 'en'),
            'head_code' => null,
            'body_code' => null,
        ];

        $user = User::create($admin);
        $user->profile()->create($adminProfile);
        $user->assignRole(ADMIN_ROLE);

        event(new Registered($user));

        $this->updateSettings($user);

    }

    private function updateSettings(User $user)
    {
        $properties = ["lead_email" => false, "lead_sms" => true, "mem_email" => false, "mem_sms" => true];
        $user->updateMultipleProperties(NOTIFICATION_SETTING_GROUP, $properties);

        $properties = ["welcome_video_watched" => false, "questionnaire_completed" => false, "meeting_scheduled" => false, "joined_facebook_group" => false];
        $user->updateMultipleProperties(ONBOARDING_GROUP_ALIAS, $properties);

        $properties = [
            "scheduling_link" => "https://calendly.com/muhammad-khawar/30min",
            "facebook_link" => "https://www.facebook.com/colten.shea.echave/",
            "advisor_message" => "Hey friend, I can't wait to connect with you & give you a roadmap for success!!!"
        ];
        $user->updateMultipleProperties(ADVISOR_SETTING_GROUP, $properties);

        $user->updateProperty('promote', 'promote_watched', false);
    }
}
