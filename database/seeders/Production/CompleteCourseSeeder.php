<?php

namespace Database\Seeders\Production;

use App\Models\CompletedLesson;
use App\Models\Course;
use Illuminate\Support\Facades\DB;
use App\Models\Video;
use App\Models\CourseSection;
use App\Models\User;
use Database\Seeders\Traits\DisableForeignKeys;
use Database\Seeders\Traits\TruncateTable;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Carbon;

class CompleteCourseSeeder extends ConfigureDatabase
{
    // private $roleModel;
    use DisableForeignKeys, TruncateTable;

    public function run()
    {
        $this->disableForeignKeys();
        $this->truncateMultiple(["completed_lessons"]);

        $CompletedCourses = $this->getConnection()
                        ->table("completed_lessons")
                        ->selectRaw("*")
                        ->get();

    //    $CompletedCourses = $CompletedCourses->take(10);
// dump($CompletedCourses);exit;
        $rawCompletedCourses = $CompletedCourses->map(function ($course) {
            return $this->buildCourse($course);
        });
        collect($rawCompletedCourses)->each(function ($course) {
            $this->storeCourse($course);
        });

        $this->enableForeignKeys();
    }

    private function storeCourse(array $courseData)
    {
        if(isset($courseData['user_id']) && isset($courseData['lesson_id']))
        DB::insert('INSERT INTO completed_lessons (user_id, lesson_id,watched) VALUES (?, ?, ?)', [
            $courseData['user_id'],
            $courseData['lesson_id'],
            true,
        ]);

    }


    private function buildCourse($course)
    {
        
        $userID =  $this->getUsers($course);
        $lessonID =  $this->getLessons($course);
        // dump($users);die;
        return [
            'user_id' => $userID,
            'lesson_id' => $lessonID,
        ];
    }

    private function getUsers($course)  {
        $userEmail = $this->getConnection()
                        ->table("users")
                        ->distinct("email")
                        ->where('id',$course->user_id)
                        ->selectRaw("email")->first();

        // $userN = User::where('email', $userEmail->email)->first();
        $userN = DB::select('SELECT id FROM users WHERE email = :email', ['email' => $userEmail->email]);

        // dump($userN[0]->id);die;
        if ($userN) {
           return  $userN[0]->id;
        } 
        return 0;
    }
    private function getLessons($course)  {
        $lessonsName = $this->getConnection()
                            ->table("course_lessons")
                            ->where('id', $course->lesson_id)
                            ->selectRaw("name")
                            ->first();
    
        if ($lessonsName) {
            $lesson_id = DB::select('SELECT id FROM course_lessons WHERE name = :name', ['name' => $lessonsName->name]);
    
            if ($lesson_id) {
                return $lesson_id[0]->id;
            }
        }
    
        return null; 
    }
    

   
}
