<?php

namespace App\Repositories;

use App\Contracts\Repositories\AdminCourseRepositoryInterface;
use App\Http\Resources\RoleResource;
use App\Models\{CourseLesson, CourseSection, Media, Video};
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
    private $videoModel;
    private $mediaModel;

    const VIDEO_SOURCES = [VIMEO, WISTIA];
    const SORT_ACTIONS_TYPE = [SORT_AFTER, SORT_BEFORE];

    public function __construct(Model $courseModel)
    {
        $this->courseModel = $courseModel;
        $this->roleModel = app(config('permission.models.role'));
        $this->lessonModel = app(CourseLesson::class);
        $this->sectionModel = app(CourseSection::class);
        $this->videoModel = app(Video::class);
        $this->mediaModel = app(Media::class);
    }

    public function fetchCoursesAudience()
    {
        $roles = $this->roleModel::excludeAdminRole()->get();
        return RoleResource::collection($roles);
    }

    public function fetchAllCourses()
    {
        $this->validatePermission();

        return $this->courseModel::with("allowedAudienceRoles", "thumbnail")->get();
    }

    public function fetchSingleCourse(string $uuid)
    {
        $this->validatePermission();

        return $this->courseModel::query()
            ->byUUID($uuid)
            ->with([
                "thumbnail",
                "allowedAudienceRoles",
                "sections.lessons.video",
                "sections" => fn($q) => $q->sorted(),
                "sections.lessons" => fn($q) => $q->sorted(),
            ])
            ->get();
    }

    public function createCourse(array $data)
    {
        $this->validatePermission();

        $thumbnailUuid = $data["thumbnail_uuid"];
        $media = $this->mediaModel::findOrFailByUuid($thumbnailUuid);
        $data["thumbnail_id"] = @$media->id;

        $course = $this->courseModel::create($data);

        if (!empty($data["thumbnail_uuid"])) {
            $rolesUuids = $data["allowed_audience_roles"];
            $roleIDs = $this->roleModel::WhereUuidIn($rolesUuids)->pluck('id')->toArray();
            $course->allowedAudienceRoles()->attach($roleIDs);
        }

        $course->load("allowedAudienceRoles", "thumbnail");

        return $course;
    }

    public function createCourseValidation(array $data)
    {
        return Validator::make($data, [
            "name" => ['required', 'string'],
            "thumbnail_uuid" => ['required', 'string', 'exists:' . get_class($this->mediaModel) . ',uuid'],
            "description" => ['nullable', 'string'],
            "allowed_audience_roles" => ['array', 'exists:' . get_class($this->roleModel) . ',uuid'],
        ]);

    }

    public function editCourse(string $courseUuid, array $data)
    {
        $this->validatePermission();

        $course = $this->courseModel::findOrFailCourseByUuid($courseUuid);

        if (!empty($data["thumbnail_uuid"])) {
            $thumbnailUuid = $data["thumbnail_uuid"];
            $media = $this->mediaModel::findOrFailByUuid($thumbnailUuid);
            $data["thumbnail_id"] = @$media->id;
        }

        $course->fill($data)->update();

        if (!empty($data["allowed_audience_roles"])) {
            $rolesUuids = $data["allowed_audience_roles"];
            $roleIDs = $this->roleModel::WhereUuidIn($rolesUuids)->pluck('id')->toArray();
            $course->allowedAudienceRoles()->sync($roleIDs);
        }

        if (empty($data["allowed_audience_roles"])){
            $course->allowedAudienceRoles()->detach();
        }

        $course->load("allowedAudienceRoles", "thumbnail");

        return $course;
    }

    public function editCourseValidation(array $data)
    {
        return Validator::make($data, [
            "name" => ['required', 'string'],
            "thumbnail_uuid" => ['nullable', 'string', 'exists:' . get_class($this->mediaModel) . ',uuid'],
            "description" => ['nullable', 'string'],
            "allowed_audience_roles" => ['array', 'exists:' . get_class($this->roleModel) . ',uuid'],
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
        $course->load("allowedAudienceRoles", "thumbnail");

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
        $section->load(["lessons" => fn($q) => $q->sorted()]);

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
        $section->load(["lessons" => fn($q) => $q->sorted()]);

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

    public function sortSections(array $data)
    {
        $this->validatePermission();

        list("source_section" => $sourceSection, "destination_section" => $destinationSection, "action_type" => $actionType) = $data;
        $actionType = $actionType ?? SORT_AFTER;   //setting default actionType value = "after"

        $sections = $this->sectionModel::whereIn("uuid", [$sourceSection, $destinationSection])->get();
        $source = $sections[0];
        $destination = $sections[1];


        $actionType == SORT_AFTER ? $source->moveAfter($destination) : $source->moveBefore($destination);

        $sections = $this->sectionModel::where("course_id", $source->course_id)->with("lessons.video")->sorted()->get();

        return $sections;
    }

    public function sortSectionsValidation(array $data)
    {
        return Validator::make($data, [
            "source_section" => ["required", "string", "exists:" . get_class($this->sectionModel) . ",uuid"],
            "destination_section" => ["required", "string", "exists:" . get_class($this->sectionModel) . ",uuid"],
            "action_type" => ["required", "string", Rule::in(self::SORT_ACTIONS_TYPE)],
        ]);
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

    public function sortLessons(array $data)
    {
        $this->validatePermission();

        list("source_lesson" => $sourceSection, "destination_lesson" => $destinationSection, "action_type" => $actionType) = $data;
        $actionType = $actionType ?? SORT_AFTER;   //setting default actionType value = "after"

        $lessons = $this->lessonModel::whereIn("uuid", [$sourceSection, $destinationSection])->get();
        $source = $lessons[0];
        $destination = $lessons[1];

        $actionType == SORT_AFTER ? $source->moveAfter($destination) : $source->moveBefore($destination);

        $sections = $this->lessonModel::where("section_id", $source->section_id)->with("video")->sorted()->get();

        return $sections;
    }

    public function sortLessonsValidation(array $data)
    {
        return Validator::make($data, [
            "source_lesson" => ["required", "string", "exists:" . get_class($this->lessonModel) . ",uuid"],
            "destination_lesson" => ["required", "string", "exists:" . get_class($this->lessonModel) . ",uuid"],
            "action_type" => ["required", "string", Rule::in(self::SORT_ACTIONS_TYPE)],
        ]);
    }

}
