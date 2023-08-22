<?php

namespace App\Repositories;

use App\Contracts\Repositories\CourseRepositoryInterface;
use App\Models\CourseLesson;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class CourseRepository implements CourseRepositoryInterface
{

    private Model $courseModel;
    private mixed $lessonModel;
    private mixed $roleModel;

    public function __construct(Model $courseModel)
    {
        $this->courseModel = $courseModel;
        $this->roleModel = app(config('permission.models.role'));
        $this->lessonModel = app(CourseLesson::class);
    }

    public function getCourseCategories()
    {
        $currenUser = currentUser();

        $courseCategories = $this->roleModel::query()
            ->when($currenUser->hasRole(ADMIN_ROLE), fn($q) => $q->selectRaw("roles.*, (true) as has_access"))
            ->when(!$currenUser->hasRole(ADMIN_ROLE), function ($q) use ($currenUser) {
                $q->selectRaw("roles.*, (SELECT count(*) from model_has_roles where model_has_roles.role_id = roles.id and model_has_roles.model_type = 'User' and model_has_roles.model_id = " . $currenUser->id . " limit 1 ) as has_access");
            })
            ->excludeAdminRole()
            ->get();

        $courseCategories->map(function ($role) {
            $role->description = ($role->name == ALL_MEMBER_ROLE) ? __("messages.course.all_member.desc") : null;
            $role->prohibited_message = $this->prohibitedCoursesMessages($role->name);
            $role->name = ($role->name == CORE_ROLE) ? $role->name . " Rank Course" : $role->name . " Course";
        });

        return $courseCategories;
    }

    protected function prohibitedCoursesMessages($role)
    {
        $messages = [
            ENAGIC_ROLE => __('messages.course.enagic.prohibited'),
            TRIFECTA_ROLE => __('messages.course.trifecta.prohibited'),
            ADVISOR_ROLE => __('messages.course.advisor.prohibited'),
            CORE_ROLE => __('messages.course.core.prohibited'),
            ACTIVE_RECRUITER_ROLE => __('messages.course.active_recruiter.prohibited'),
        ];

        return array_key_exists($role, $messages) ? $messages[$role] : null;
    }

    public function getCourseByCategory(string $categoryUuid)
    {
        $perPage = request()->input('per_page') ?? 10;

        $user = currentUser();
        $role = $this->roleModel::whereUuidIn([$categoryUuid])->firstOrFail();

        /**
         * Here validating if user don't have admin or appropriate role of particular
         * courses-category courses list then abort
         */
        $hasAccessToCourses = $user->roles()->whereIn('roles.name', [ADMIN_ROLE, $role->name])->exists();
        abort_if(
            !$hasAccessToCourses,
            Response::HTTP_FORBIDDEN,
            __('auth.roles.access_denied')
        );

        $courses = $this->courseModel::query()
            ->whereHas("allowedAudienceRoles", fn($q) => $q->whereIn('roles.id', [$role->id]))
            ->paginate($perPage)->withQueryString();

        return $courses;
    }

    public function fetchLessonByCourseUuid(string $uuid)
    {
        $userId = currentUserId();

        $course = $this->courseModel::query()->byUUID($uuid)->firstOrFail();

        $lessonsQuery = $course->sections();
        $lessonsQuery->with([
            "lessons.video",
            "lessons" => fn($q) => $q->selectRaw("*,
            (SELECT count(*) from completed_lessons where completed_lessons.lesson_id = course_lessons.id
             and completed_lessons.watched = true and completed_lessons.user_id =" . $userId . " limit 1) as watched")->orderBy("course_lessons.position"),
        ]);
        $lessons = $lessonsQuery->orderBy("course_sections.position")->get();

        return $lessons;
    }

    public function markLessonStatus(array $data)
    {
        $uuid = $data['lesson_uuid'];
        $status = $data['status'];
        $userId = currentUserId();

        $lesson = $this->lessonModel::byUUID($uuid)->firstOrFail();
        $lesson->lessonUsers()->toggle([$userId => ["watched" => $status]]);
        $lesson->load("video");
        $lesson->loadCount(["lessonUsers as watched" => fn($q) => $q->where('users.id', $userId)]);

        return $lesson;
    }

    public function markLessonStatusValidation(array $data)
    {
        return Validator::make($data, [
            "lesson_uuid" => ['required', 'string', 'exists:' . CourseLesson::class . ',uuid'],
            "status" => ['required', 'boolean']
        ]);
    }

}
