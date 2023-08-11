<?php

namespace App\Contracts\Repositories;

interface AdminCourseRepositoryInterface
{
    public function fetchCoursesAudience();

    public function fetchAllCourses();

    public function fetchSingleCourse(string $uuid);

}
