<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\CourseRepositoryInterface;
use App\Http\Resources\CourseCategoryResource;
use App\Http\Resources\CourseResource;
use App\Http\Resources\SectionResouce;
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
            $courseCategories = CourseCategoryResource::collection($courseCategories);

            return response()->success(__("messages.course_categories.fetched"), $courseCategories);

        } catch (\Exception $exception) {
            return $this->handleException($exception);
        }
    }

    public function coursesByCategory($uuid)
    {
        try {
            $courses = $this->courseRepository->getCourseByCategory($uuid);
            $courses = (CourseResource::collection($courses))->response()->getData(true);

            return response()->success(__("messages.courses.fetched"), $courses);

        } catch (\Exception $exception) {
            return $this->handleException($exception);
        }
    }

    public function courseLessons($uuid)
    {
        try {
            $sections= $this->courseRepository->fetchLessonByCourseUuid($uuid);
            $lessons= SectionResouce::collection($sections);

            return response()->success(__("messages.courses_lessons.fetched"), $lessons);

        } catch (\Exception $exception) {
            return $this->handleException($exception);
        }
    }

    public function markLessonStatus()
    {

    }
}
