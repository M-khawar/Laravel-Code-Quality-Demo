<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\CourseRepositoryInterface;
use App\Http\Resources\CourseCategoryResource;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function __construct(public CourseRepositoryInterface $courseRepository)
    {
    }

    public function categories()
    {
        try {
            $courseCategories = $this->courseRepository->getCourseCategories();
            $courseCategories= CourseCategoryResource::collection($courseCategories);

            return response()->success(__("messages.course_categories.fetched"), $courseCategories);

        } catch (\Exception $exception) {
            return $this->handleException($exception);
        }
    }

    public function coursesByCategory()
    {

    }

    public function courseLessons()
    {

    }

    public function markLessonStatus()
    {

    }
}
