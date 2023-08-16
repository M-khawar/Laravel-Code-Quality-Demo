<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\AdminCourseRepositoryInterface;
use App\Http\Resources\{CourseResource, SectionResouce};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

            DB::beginTransaction();
            $this->adminCourseRepository->createCourseValidation($data)->validate();
            $course = $this->adminCourseRepository->createCourse($data);
            DB::commit();

            $course = new CourseResource($course);
            return response()->success(__('messages.admin_course.created'), $course);

        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->handleException($exception);
        }
    }

    public function editCourse($uuid, Request $request)
    {
        try {
            $data = $request->input();

            DB::beginTransaction();
            $this->adminCourseRepository->editCourseValidation($data)->validate();
            $course = $this->adminCourseRepository->editCourse($uuid, $data);
            DB::commit();

            $course = new CourseResource($course);
            return response()->success(__('messages.admin_course.updated'), $course);

        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->handleException($exception);
        }
    }

    public function destroyCourse($uuid)
    {
        try {
            DB::beginTransaction();
            $this->adminCourseRepository->deleteCourse($uuid);
            DB::commit();

            return response()->message(__("messages.admin_course.deleted"));

        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->handleException($exception);
        }
    }

    public function createSection(Request $request)
    {
        try {
            $data = $request->input();

            DB::beginTransaction();
            $this->adminCourseRepository->createSectionValidation($data)->validate();
            $section = $this->adminCourseRepository->createSection($data);
            DB::commit();

            $section = new SectionResouce($section);
            return response()->success(__('messages.admin_course_section.created'), $section);

        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->handleException($exception);
        }
    }

    public function editSection(Request $request)
    {
        try {
            $data = $request->input();

            DB::beginTransaction();
            $this->adminCourseRepository->editSectionValidation($data)->validate();
            $section = $this->adminCourseRepository->editSection($data);
            DB::commit();

            $section = new SectionResouce($section);
            return response()->success(__('messages.admin_course_section.updated'), $section);

        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->handleException($exception);
        }
    }

    public function destroySection($uuid)
    {
        try {
            DB::beginTransaction();
            $this->adminCourseRepository->deleteSection($uuid);
            DB::commit();

            return response()->message(__("messages.admin_course_section.deleted"));

        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->handleException($exception);
        }
    }
}
