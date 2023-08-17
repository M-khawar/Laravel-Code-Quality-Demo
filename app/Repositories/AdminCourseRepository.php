<?php

namespace App\Repositories;

use App\Contracts\Repositories\AdminCourseRepositoryInterface;
use App\Http\Resources\RoleResource;
use App\Models\{CourseLesson, CourseSection, Video};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class AdminCourseRepository implements AdminCourseRepositoryInterface
{
    private Model $courseModel;
    private $roleModel;
    private $lessonModel;
    private $sectionModel;
    private mixed $videoModel;

    const VIDEO_SOURCES = [VIMEO, WISTIA];

    public function __construct(Model $courseModel)
    {
        $this->courseModel = $courseModel;
        $this->roleModel = app(config('permission.models.role'));
        $this->lessonModel = app(CourseLesson::class);
        $this->sectionModel = app(CourseSection::class);
        $this->videoModel = app(Video::class);
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

    public function createCourseValidation(array $data)
    {
        return Validator::make($data, [
            "name" => ["required", "string"],
            "description" => ["required", "string"],
            "allowed_audience_roles" => ["required", 'exists:' . get_class($this->roleModel) . ',uuid'],
        ]);

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

    public function editCourseValidation(array $data)
    {
        return Validator::make($data, [
            "name" => ["required", "string"],
            "description" => ["required", "string"],
            "allowed_audience_roles" => ["required", 'exists:' . get_class($this->roleModel) . ',uuid'],
        ]);

    }

    public function deleteCourse(string $courseUuid)
    {
        $course = $this->courseModel::findOrFailCourseByUuid($courseUuid);

        $course->allowedAudienceRoles()->detach();
        $course->lessons()->delete();
        $course->sections()->delete();

        return $course->delete();
    }

    protected function validatePermission()
    {
        $user = currentUser();
        abort_if(!$user->hasRole(ADMIN_ROLE), Response::HTTP_FORBIDDEN, __("auth.roles.access_denied"));

        return $user;
    }

    public function updateCoursePermissions(array $data)
    {
        $this->validatePermission();

        $roleID = $this->roleModel::ByUUID($data["toggleable_audience_role"])->pluck('id')->toArray();
        $course = $this->courseModel::findOrFailCourseByUuid($data["course_uuid"]);

        $course->allowedAudienceRoles()->toggle($roleID);
        $course->load("allowedAudienceRoles");

        return $course;
    }

    public function updateCoursePermissionsValidation(array $data)
    {
        return Validator::make($data, [
            "course_uuid" => ["required", "string", "exists:" . get_class($this->courseModel) . ",uuid"],
            "toggleable_audience_role" => ["required", "string", "exists:" . get_class($this->roleModel) . ",uuid"],
        ]);
    }

    public function createSection(array $data)
    {
        $this->validatePermission();

        $courseUuid = $data["course_uuid"];
        $course = $this->courseModel::findOrFailCourseByUuid($courseUuid);

        $section = $course->sections()->create($data);
        $section->load("lessons");

        return $section;
    }

    public function createSectionValidation(array $data)
    {
        return Validator::make($data, [
            "course_uuid" => ["required", "string", "exists:" . get_class($this->courseModel) . ",uuid"],
            "name" => ["required", "string"],
            "description" => ["nullable", "string"],
        ]);
    }

    public function editSection(array $data)
    {
        $this->validatePermission();

        $sectionUuid = $data["section_uuid"];
        $section = $this->sectionModel::findOrFailSectionByUuid($sectionUuid);

        $section->fill($data)->save();
        $section->load("lessons");

        return $section;
    }

    public function editSectionValidation(array $data)
    {
        return Validator::make($data, [
            "section_uuid" => ["required", "string", "exists:" . get_class($this->sectionModel) . ",uuid"],
            "name" => ["required", "string"],
            "description" => ["nullable", "string"],
        ]);
    }

    public function deleteSection(string $sectionUuid)
    {
        $this->validatePermission();

        $section = $this->sectionModel::findOrFailSectionByUuid($sectionUuid);
        $section->lessons()->delete();
        return $section->delete();
    }

    public function createLesson(array $data)
    {
        $this->validatePermission();

        $sectionUuid = $data["section_uuid"];
        $section = $this->sectionModel::findOrFailSectionByUuid($sectionUuid);

        $videoDict = $this->buildVideoDict($data);
        $video = $this->videoModel::firstOrCreate($videoDict, ["slug" => generateVideoSlug()]);

        $data['video_id'] = $video->id;
        $lessonDict = $this->buildLessonDic($data);
        $lesson = $section->lessons()->create($lessonDict);
        $lesson->load("video");

        return $lesson;
    }

    public function createLessonValidation(array $data)
    {
        return Validator::make($data, [
            "section_uuid" => ["required", "string", "exists:" . get_class($this->sectionModel) . ",uuid"],
            "name" => ["required", "string"],
            "description" => ["nullable", "string"],
            "resources" => ["nullable", "string"],
            "video_link" => ["required"],
            "video_source" => ["required", Rule::In(self::VIDEO_SOURCES)],
        ]);
    }

    public function editLesson(array $data)
    {
        $this->validatePermission();

        $lessonUuid = $data["lesson_uuid"];
        $lesson = $this->lessonModel::findOrFailLessonByUuid($lessonUuid);

        $videoDict = $this->buildVideoDict($data);
        $video = $this->videoModel::firstOrCreate($videoDict, ["slug" => generateVideoSlug()]);

        $data['video_id'] = $video->id;
        $lessonDict = $this->buildLessonDic($data);
        $lesson->update($lessonDict);
        $lesson->load("video");

        return $lesson;
    }

    public function editLessonValidation(array $data)
    {
        return Validator::make($data, [
            "lesson_uuid" => ["required", "string", "exists:" . get_class($this->lessonModel) . ",uuid"],
            "name" => ["required", "string"],
            "description" => ["nullable", "string"],
            "resources" => ["nullable", "string"],
            "video_link" => ["required"],
            "video_source" => ["required", Rule::In(self::VIDEO_SOURCES)],
        ]);
    }

    protected function buildVideoDict(array $data): array
    {
        return [
            "link" => $data["video_link"],
            "source" => $data["video_source"]
        ];
    }

    protected function buildLessonDic(array $data): array
    {
        return [
            "video_id" => $data["video_id"],
            "name" => $data["name"],
            "description" => $data["description"],
            "resources" => $data["resources"],
        ];
    }

    public function deleteLesson(string $lessonUuid)
    {
        $this->validatePermission();

        $lesson = $this->lessonModel::findOrFailLessonByUuid($lessonUuid);
        return $lesson->delete();
    }

}
