<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\AdminCourseRepositoryInterface;
use App\Contracts\Repositories\CourseRepositoryInterface;
use App\Http\Resources\{CourseCategoryResource, CourseResource, LessonResource, SectionResouce};
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function __construct(
        public CourseRepositoryInterface      $courseRepository,
        public AdminCourseRepositoryInterface $adminCourseRepository
    )
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
            $sections = $this->courseRepository->fetchLessonByCourseUuid($uuid);
            $lessons = SectionResouce::collection($sections);

            return response()->success(__("messages.courses_lessons.fetched"), $lessons);

        } catch (\Exception $exception) {
            return $this->handleException($exception);
        }
    }

    public function markLessonStatus(Request $request)
    {
        try {
            $data = $request->input();
            $this->courseRepository->markLessonStatusValidation($data)->validate();
            $lesson = $this->courseRepository->markLessonStatus($data);
            $lessons = new LessonResource($lesson);

            return response()->success(__("messages.lesson.status_marked"), $lessons);

        } catch (\Exception $exception) {
            return $this->handleException($exception);
        }
    }

    /*** Admin-Courses Methods ***/
    public function coursesAudience()
    {
        try {
            $courseAudienceRoles = $this->adminCourseRepository->fetchCoursesAudience();

            return response()->success(__('auth.roles.fetched'), $courseAudienceRoles);

        } catch (\Exception $exception) {
            return $this->handleException($exception);
        }
    }

    public function adminCourses()
    {
        try {
            $adminCourses = $this->adminCourseRepository->fetchAllCourses();
            $adminCourses = CourseResource::collection($adminCourses);

            return response()->success(__('messages.admin_courses.fetched'), $adminCourses);

        } catch (\Exception $exception) {
            return $this->handleException($exception);
        }
    }

    public function adminSingleCourse($uuid)
    {
        try {
            $adminCourse = $this->adminCourseRepository->fetchSingleCourse($uuid);
            $adminCourse = CourseResource::collection($adminCourse);

            return response()->success(__('messages.admin_course.fetched'), $adminCourse);

        } catch (\Exception $exception) {
            return $this->handleException($exception);
        }
    }

    public function createCourse(Request $request)
    {
        try {
            $data = $request->input();
            $this->adminCourseRepository->createCourseValidation($data)->validate();
            $course = $this->adminCourseRepository->createCourse($data);
            $course = new CourseResource($course);

            return response()->success(__('messages.admin_course.created'), $course);

        } catch (\Exception $exception) {
            return $this->handleException($exception);
        }
    }
}
