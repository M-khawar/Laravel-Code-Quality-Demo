<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\AdminCourseRepositoryInterface;
use App\Http\Resources\{CourseResource, LessonResource, SectionResouce};
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

    public function updatePermissions(Request $request)
    {
        try {
            $data = $request->input();

            DB::beginTransaction();
            $this->adminCourseRepository->updateCoursePermissionsValidation($data)->validate();
            $course = $this->adminCourseRepository->updateCoursePermissions($data);
            DB::commit();

            $course = new CourseResource($course);
            return response()->success(__('messages.admin_course.permissions_updated'), $course);

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

    public function sortSection(Request $request)
    {
        try {
            $data = $request->input();

            $this->adminCourseRepository->sortSectionsValidation($data)->validate();
            $sections = $this->adminCourseRepository->sortSections($data);

            $sections = SectionResouce::collection($sections);
            return response()->success(__('messages.admin_course_section.sorted'), $sections);

        } catch (\Exception $exception) {
            return $this->handleException($exception);
        }
    }

    public function createLesson(Request $request)
    {
        try {
            $data = $request->input();

            DB::beginTransaction();
            $this->adminCourseRepository->createLessonValidation($data)->validate();
            $lesson = $this->adminCourseRepository->createLesson($data);
            DB::commit();

            $lesson = new LessonResource($lesson);
            return response()->success(__('messages.admin_course_lesson.created'), $lesson);

        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->handleException($exception);
        }
    }

    public function editLesson(Request $request)
    {
        try {
            $data = $request->input();

            DB::beginTransaction();
            $this->adminCourseRepository->editLessonValidation($data)->validate();
            $lesson = $this->adminCourseRepository->editLesson($data);
            DB::commit();

            $lesson = new LessonResource($lesson);
            return response()->success(__('messages.admin_course_lesson.updated'), $lesson);

        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->handleException($exception);
        }

    }

    public function destroyLesson($uuid)
    {
        try {
            DB::beginTransaction();
            $this->adminCourseRepository->deleteLesson($uuid);
            DB::commit();

            return response()->message(__("messages.admin_course_lesson.deleted"));

        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->handleException($exception);
        }
    }

    public function sortLesson(Request $request)
    {
        try {
            $data = $request->input();

            $this->adminCourseRepository->sortLessonsValidation($data)->validate();
            $lessons = $this->adminCourseRepository->sortLessons($data);

            $lessons = LessonResource::collection($lessons);
            return response()->success(__('messages.admin_course_lesson.sorted'), $lessons);

        } catch (\Exception $exception) {
            return $this->handleException($exception);
        }
    }

    public function adminStats(Request $request)
    {
        try {
            $data= $request->input();
            $this->adminCourseRepository->adminStatsValidation($data)->validate();
            $period = $request->period ?? "all";

            $filterRange = [
                "start_date" => @$request->start_date,
                "end_date" => @$request->end_date,
            ];

            $dateRange = $this->adminCourseRepository->periodConversion($period, $filterRange);
            $stats = $this->adminCourseRepository->adminStats($dateRange->start_date, $dateRange->end_date);

            return response()->success("Successfully Stats Fetched.", $stats);

        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

}
