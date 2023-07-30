<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lead>
 */
class LeadFactory extends Factory
{
    public function definition()
    {
        $funnels = [MASTER_FUNNEL, LIVE_OPPORTUNITY_CALL_FUNNEL];
        return [
            "name" => fake()->name(),
            "email" => $this->faker->unique()->safeEmail(),
            "instagram" => null,
            "advisor_id" => config('default_settings.default_advisor'),
            "affiliate_id" => config('default_settings.default_advisor'),
            "funnel_type" => $funnels[rand(0, 1)],
        ];
    }
}
