<?php

namespace Database\Seeders\Production;

use App\Models\Course;
use App\Models\Media;
use App\Models\Role;
use App\Models\Video;
use App\Models\CourseSection;
use App\Models\User;
use Database\Seeders\Traits\DisableForeignKeys;
use Database\Seeders\Traits\TruncateTable;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Carbon;

class CourseSeeder extends ConfigureDatabase
{
    // private $roleModel;
    use DisableForeignKeys, TruncateTable;

    public function run()
    {
        $this->disableForeignKeys();
        $this->truncateMultiple(["courses","has_course_permissions","course_sections"]);
        $courses = $this->getConnection()
                        ->table("courses")
                        ->selectRaw("*")
                        ->get();

    //    $courses = $courses->take(10);
// dump($courses);exit;
        $rawcourses = $courses->map(function ($course) {
            return $this->buildCourse($course);
        });
        // dd($rawcourses);
        collect($rawcourses)->each(function ($course) {
            $this->storeCourse($course);
        });

        $this->enableForeignKeys();
    }
    private function buildCourse($course)
    {

        $timestamp = (int)$course->created_at;
        $timestamp = intval($timestamp / 1000);
        $created_at = $this->timeStampConversion($timestamp);

        return [
            'id'=> $course->id,
            'name' => $course->name,
            'description' => $course->description,
            'thumbnail' => $course->thumbnail,
            'position' => $course->position,
            'created_at' => $created_at,
            'updated_at' => $created_at,
            'roles' => $this->buildRoles($course)  //logic
           
        ];
    }

    private function storeCourse(array $courseData)
    {
        // dump($courseData);exit;
        $roles = $courseData["roles"];
        $courseId = $courseData["id"];

        unset($courseData["roles"]);
        unset($courseData["id"]);
        $courseData["thumbnail_id"] = $this->storethumbnail($courseData);
        $courseData["position"] = $courseData["position"] ?? null;
        $course = Course::firstOrCreate([...$courseData]);
        if(count($roles) > 1 ){
            unset($roles[0]);
        }
        foreach ($roles as $role) {
            $roleId = Role::where('name', $role)->value('id');
            // dump($roleId);exit;
            $course->allowedAudienceRoles()->attach($roleId);
        }
        
         // Store course sections
         $courseSections = $this->getConnection()
         ->table("course_sections")
         ->where('course_id', $courseId)
         ->selectRaw("*")
         ->get();
        //  dump($courseSections);exit;
        $rawCourseSections = $courseSections->map(function ($section) use ($course) {
            return $this->buildCourseSection($section, $course->id);
        });
        // dump($rawCourseSections);exit;
        $i = 1; 
        collect($rawCourseSections)->each(function ($courseSection) use ($course,&$i) {
            unset($courseSection['lesson_count']);
            // CourseSection::create($courseSection);
            $lessons = $this->getConnection()
                            ->table("course_lessons")
                            ->where('section_id', $courseSection['id'])
                            ->selectRaw("*")
                            ->get();
            unset($courseSection['id']);
            // dump($lessons);exit;
            $section = $course->sections()->create($courseSection);
            $i = $this->storeCourseLessons($lessons, $section,$i);
            
            });
       
        // dump($courseData);

    }

    private function storeCourseLessons($lessons,$section,$i)
    {
        
        $rawCourselessons = $lessons->map(function ($lesson) use ($section, &$i) {
            $courseLesson = $this->buildCourseLessons($lesson, $section->id,$i);
            $i++; 
            return $courseLesson;
        });
        collect($rawCourselessons)->each(function ($lesson) use ($section) {
            $video = $lesson['video'];
            unset($lesson['video']);
            $video = Video::create([...$video]);
            $section->lessons()->create([...$lesson, "video_id" => $video->id]);
        });
        return $i;
    }
    private function buildCourseLessons($lesson, $section_Id,$i)
    {
        $timestamp = (int)$lesson->created_at;
        $timestamp = intval($timestamp / 1000);
        $created_at = $this->timeStampConversion($timestamp);

        return [
            // 'id'   => $lesson->id,
            'name' => $lesson->name,
            'description' => $lesson->description,
            'position' => $lesson->position,
            'resources' => $lesson->resources,
            'created_at' => $created_at,
            'updated_at' => $created_at,
            'section_id' => $section_Id,  //logic
            "video" => [
                        "link" => $lesson->vimeo_link,
                        "source" => $lesson->is_wistia == 1 ? WISTIA : VIMEO,
                        "slug" => "course_" . $i . "_section_" . $section_Id,
                        'created_at' => $created_at,
                        'updated_at' => $created_at,
                    ],
        ];
    }
    private function buildCourseSection($section, $course_Id)
    {
        $timestamp = (int)$section->created_at;
        $timestamp = intval($timestamp / 1000);
        $created_at = $this->timeStampConversion($timestamp);

        return [
            'id'   => $section->id,
            'name' => $section->name,
            'description' => $section->description,
            'position' => $section->position,
            'lesson_count' => $section->lesson_count,
            'created_at' => $created_at,
            'updated_at' => $created_at,
            // 'course_id' => $course_Id  //logic
           
        ];
    }

    private function storethumbnail(array &$course)
    {
        if (isset($course["thumbnail"])) {
            $media = Media::firstOrCreate(["path" => $course["thumbnail"]], ["source" => SPACES_STORAGE]);
            $thumbnail_id = $media->id;
            // dump($thumbnail_id);exit;
            unset($course["thumbnail"]);
        }

        
        return $thumbnail_id;
    }


   
    private function buildRoles($course)
    {
        $roles = [ALL_MEMBER_ROLE];

        if (@$course->is_enagic) array_push($roles, ENAGIC_ROLE,);
        if (@$course->is_advisor) array_push($roles, ADVISOR_ROLE,);
        if (@$course->is_trifecta) array_push($roles, TRIFECTA_ROLE,);
        if (@$course->is_core) array_push($roles, CORE_ROLE);
        if (@$course->is_active_recruiter) array_push($roles, ACTIVE_RECRUITER_ROLE,);

        return $roles;
    }
}
