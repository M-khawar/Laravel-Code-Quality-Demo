<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    public function definition()
    {
        $funnels = [MASTER_FUNNEL, LIVE_OPPORTUNITY_CALL_FUNNEL];

        /*$year = rand(2022, 2023);
        $month = rand(1, 12);
        $day = rand(1, 28);

        $date = Carbon::create($year,$month ,$day , 0, 0, 0);*/


        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'instagram' => null,
            'phone' => fake()->phoneNumber(),
            'avatar' => null,
            'advisor_id' => config('default_settings.default_advisor'),
            'affiliate_id' => config('default_settings.default_advisor'),
            'affiliate_code' => null,
            'funnel_type' => $funnels[rand(0, 1)],
            'remember_token' => Str::random(10),
            'advisor_date' => null,
            'email_verified_at' => now(),
        ];
    }

    public function unverified()
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function configure()
    {
        return $this->afterCreating(function (User $user) {
            $user->profile()->create([
                'display_name' => $user->name,
                'display_text' => __('messages.default_display_text', locale: 'en'),
                'is_enagic' => true,
                'enagic_date' => now(),
            ]);

            $user->address()->create([
                "city" => fake()->city(),
                "state" => fake()->country,
                "zipcode" => rand(11111, 999999),
                "address" => fake()->address(),
            ]);

            $user->assignRole(ALL_MEMBER_ROLE);

            event(new Registered($user));
        });
    }
}
