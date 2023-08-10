<?php

namespace App\Contracts\Repositories;

interface CourseRepositoryInterface
{
    public function getCourseCategories();

    public function getCourseByCategory(string $categoryUuid);

    public function fetchLessonByCourseUuid(string $uuid);
}
