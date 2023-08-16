<?php

namespace App\Contracts\Repositories;

interface AdminCourseRepositoryInterface
{
    public function fetchCoursesAudience();

    public function fetchAllCourses();

    public function fetchSingleCourse(string $uuid);

    public function createCourse(array $data);

    public function createCourseValidation(array $data);

    public function editCourse(string $courseUuid, array $data);

    public function editCourseValidation(array $data);

}