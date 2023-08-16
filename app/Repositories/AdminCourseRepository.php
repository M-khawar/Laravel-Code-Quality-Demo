<?php

namespace App\Repositories;

use App\Contracts\Repositories\AdminCourseRepositoryInterface;
use App\Http\Resources\RoleResource;
use App\Models\CourseLesson;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AdminCourseRepository implements AdminCourseRepositoryInterface
{
    public function __construct(Model $courseModel)
    {
        $this->courseModel = $courseModel;
        $this->roleModel = app(config('permission.models.role'));
        $this->lessonModel = app(CourseLesson::class);
    }

    public function fetchCoursesAudience()
    {
        $roles = $this->roleModel::excludeAdminRole()->get();
        return RoleResource::collection($roles);
    }

    public function fetchAllCourses()
    {
        $this->validatePermission();

        return $this->courseModel::with("allowedAudienceRoles")->get();
    }

    public function fetchSingleCourse(string $uuid)
    {
        $this->validatePermission();

        return $this->courseModel::query()
            ->byUUID($uuid)
            ->with("allowedAudienceRoles", "sections.lessons.video")
            ->get();
    }

    public function createCourse(array $data)
    {
        $this->validatePermission();

        $rolesUuids = $data["allowed_audience_roles"];
        $roleIDs = $this->roleModel::WhereUuidIn($rolesUuids)->pluck('id')->toArray();

        $course = $this->courseModel::create($data);
        $course->allowedAudienceRoles()->attach($roleIDs);
        $course->load("allowedAudienceRoles");

        return $course;
    }

    public function editCourse(string $courseUuid, array $data)
    {
        $this->validatePermission();

        $course = $this->courseModel::findOrFailCourseByUuid($courseUuid);

        $rolesUuids = $data["allowed_audience_roles"];
        $roleIDs = $this->roleModel::WhereUuidIn($rolesUuids)->pluck('id')->toArray();

        $course->fill($data)->update();
        $course->allowedAudienceRoles()->sync($roleIDs);
        $course->load("allowedAudienceRoles");

        return $course;
    }

    public function deleteCourse(string $courseUuid)
    {
        $course = $this->courseModel::findOrFailCourseByUuid($courseUuid);

        $course->allowedAudienceRoles()->detach();
        $course->lessons()->delete();
        $course->sections()->delete();

        $course->delete();
    }


    public function createCourseValidation(array $data)
    {
        return Validator::make($data, [
            "name" => ["required", "string"],
            "description" => ["required", "string"],
            "allowed_audience_roles" => ["required", 'exists:' . get_class($this->roleModel) . ',uuid'],
        ]);

    }

    public function editCourseValidation(array $data)
    {
        return Validator::make($data, [
            "name" => ["required", "string"],
            "description" => ["required", "string"],
            "allowed_audience_roles" => ["required", 'exists:' . get_class($this->roleModel) . ',uuid'],
        ]);

    }

    protected function validatePermission()
    {
        $user = currentUser();
        abort_if(!$user->hasRole(ADMIN_ROLE), Response::HTTP_FORBIDDEN, __("auth.roles.access_denied"));

        return $user;
    }
}
