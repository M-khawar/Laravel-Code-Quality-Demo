<?php

namespace Database\Seeders\DummyData;

use App\Models\Course;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    private $roleModel;

    public function __construct()
    {
        $this->roleModel = app(config('permission.models.role'));
    }

    public function run()
    {
        $courses = [
            [
                "name" => "LIVE Recorded Closing Calls ",
                "description" => "Learn from the best with real life examples",
                "thumbnail" => "https://docs-prod.nyc3.digitaloceanspaces.com/Screen%20Shot%202021-03-03%20at%202.38.35%20PM.png",
                "roles" => [TRIFECTA_ROLE],
            ],
            [
                "name" => "Wednesday Night Distributor Trainings",
                "description" => null,
                "thumbnail" => "https://docs-prod.nyc3.digitaloceanspaces.com/download-3.jpg",
                "roles" => [ENAGIC_ROLE],
            ],
            [
                "name" => "30 Day Roadmap",
                "description" => "You Suddenly Lose Everything... What Would You do From Day 1 To Day 30 To Save Yourself!",
                "thumbnail" => "https://docs-prod.nyc3.digitaloceanspaces.com/30%20Day%20Roadmap%20Training%20box.png",
                "roles" => [TRIFECTA_ROLE],
            ],
            [
                "name" => "LIVE EVENT RECORDINGS",
                "description" => "Learn all the best insider secrets without having to pay for a plane ticket & hotel room",
                "thumbnail" => "https://r2f.sfo2.digitaloceanspaces.com/2%20Live%20Event%20Recordings%20ipad1.png",
                "roles" => [TRIFECTA_ROLE],
            ],
            [
                "name" => "Bootcamp Replay",
                "description" => null,
                "thumbnail" => "https://docs-prod.nyc3.digitaloceanspaces.com/Bootcamp.jpeg",
                "roles" => [ENAGIC_ROLE],
            ],
            [
                "name" => "Power Huddle Replay",
                "description" => null,
                "thumbnail" => "https://docs-prod.nyc3.digitaloceanspaces.com/Huddle.jpeg",
                "roles" => [ENAGIC_ROLE],
            ],
            [
                "name" => "TikTok Master class",
                "description" => "Learn the top secrets tips & tricks to master TikTok & impact lives!",
                "thumbnail" => "https://docs-prod.nyc3.digitaloceanspaces.com/TIKTOK.png",
                "roles" => [ENAGIC_ROLE],
            ],
            [
                "name" => "Product Overview",
                "description" => null,
                "thumbnail" => "https://docs-prod.nyc3.digitaloceanspaces.com/Enagics%20Trifecta%20Package%20bag.png",
                "roles" => [ENAGIC_ROLE],
            ],
            [
                "name" => "Your First 90 Days",
                "description" => null,
                "thumbnail" => "https://docs-prod.nyc3.digitaloceanspaces.com/start.jpg",
                "roles" => [ENAGIC_ROLE],
            ],
            [
                "name" => "90 Day Run",
                "description" => null,
                "thumbnail" => "https://docs-prod.nyc3.digitaloceanspaces.com/7350_-_first_90_days_templatesm_600x314.jpeg",
                "roles" => [ENAGIC_ROLE],
            ],
            [
                "name" => "Leadership",
                "description" => null,
                "thumbnail" => "https://docs-prod.nyc3.digitaloceanspaces.com/d19d5sz0wkl0lu.cloudfront.jpg",
                "roles" => [TRIFECTA_ROLE],
            ],
            [
                "name" => "Active Recruiter",
                "description" => null,
                "thumbnail" => null,
                "roles" => [ACTIVE_RECRUITER_ROLE],
            ],
            [
                "name" => "Morning Huddle Replays",
                "description" => null,
                "thumbnail" => "https://docs-prod.nyc3.digitaloceanspaces.com/Daily%20Morning%20Power%20Huddles%20ipad.png",
                "roles" => [TRIFECTA_ROLE],
            ],
            [
                "name" => "Advisor 101",
                "description" => null,
                "thumbnail" => "https://docs-prod.nyc3.digitaloceanspaces.com/leadership.jpg",
                "roles" => [ADVISOR_ROLE],
            ],
            [
                "name" => "Capital Secrets",
                "description" => null,
                "thumbnail" => "https://docs-prod.nyc3.digitaloceanspaces.com/Capital%20Secrets%20Training%20ipad.png",
                "roles" => [ALL_MEMBER_ROLE],
            ],
        ];

        foreach ($courses as $course) {
            $roles = $course['roles'];
            unset($course['roles']);
            $rolesId= $this->roleModel::whereIn('name', $roles)->pluck('id')->toArray();

            $createdCourse = Course::create($course);
            $createdCourse->allowedAudienceRoles()->attach($rolesId);
        }
    }
}
