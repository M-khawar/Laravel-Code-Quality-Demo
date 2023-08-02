<?php

namespace Database\Factories;

use App\Contracts\Repositories\CalendarRepositoryInterface;
use App\Contracts\Repositories\UserRepositoryInterface;
use App\Models\Calendar;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Calendar>
 */
class CalendarFactory extends Factory
{
    private mixed $calendarRepository;
    private mixed $userRepository;

    public function __construct($count = null, ?Collection $states = null, ?Collection $has = null, ?Collection $for = null, ?Collection $afterMaking = null, ?Collection $afterCreating = null, $connection = null, ?Collection $recycle = null)
    {
        parent::__construct($count, $states, $has, $for, $afterMaking, $afterCreating, $connection, $recycle);
        $this->calendarRepository = app(CalendarRepositoryInterface::class);
        $this->userRepository = app(UserRepositoryInterface::class);
    }

    public function definition()
    {
        $colors = $this->calendarRepository::calenderColors;

        return [
            "title" => fake()->text(20),
            "description" => fake()->text(50),
            "link" => fake()->url(),
            "color" => $colors[rand(0, count($colors) - 1)],
            "display_date" => now()->toDateString(),
            "start_time" => now()->toTimeString(),
            "end_time" => now()->toTimeString()
        ];
    }

    public function configure()
    {
        $roles = $this->userRepository->getRolesExceptAdmin()->pluck('id')->toArray();

        return $this->afterCreating(function (Calendar $calendar) use ($roles) {
            $calendar->allowedAudienceRoles()->attach($roles[array_rand($roles, 1)]);
        });
    }
}
