<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\AdminCourseRepositoryInterface;
use App\Http\Resources\CourseResource;
use Illuminate\Http\Request;

class AdminCourseController extends Controller
{
    public function __construct(public AdminCourseRepositoryInterface $adminCourseRepository)
    {
    }

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

    public function editCourse($uuid, Request $request)
    {
        try {
            $data = $request->input();
            $this->adminCourseRepository->editCourseValidation($data)->validate();
            $course= $this->adminCourseRepository->editCourse($uuid, $data);
            $course = new CourseResource($course);

            return response()->success(__('messages.admin_course.created'), $course);

        } catch (\Exception $exception) {
            return $this->handleException($exception);
        }
    }
}
