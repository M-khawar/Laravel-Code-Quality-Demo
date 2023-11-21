<?php

namespace Database\Seeders\Production;

use App\Models\Answer;
use App\Models\CompletedLesson;
use App\Models\Course;
use Illuminate\Support\Facades\DB;
use App\Models\Video;
use App\Models\CourseSection;
use App\Models\Question;
use App\Models\User;
use Database\Seeders\Traits\DisableForeignKeys;
use Database\Seeders\Traits\TruncateTable;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Carbon;

class QuestionAndAnswersSeeder extends ConfigureDatabase
{
    // private $roleModel;
    use DisableForeignKeys, TruncateTable;

    public function run()
    {
        $this->disableForeignKeys();
        $this->truncateMultiple(["questions","answers"]);

        $questions = $this->getConnection()
                        ->table("form_questions")
                        ->selectRaw("*")
                        ->get();

    //    $questions = $questions->take(10);

        $rawquestions = $questions->map(function ($question) {
            return $this->buildQuestion($question);
        });
    //    dump($rawquestions);die;
        collect($rawquestions)->each(function ($course) {
            $this->storeQestion($course);
        });

        $this->enableForeignKeys();
    }

    private function storeQestion(array $questionData)
    {
        $video = $questionData['video'];
        $questionID = $questionData['id'];
        unset($questionData['video']);
        unset($questionData['id']);

        $video = Video::create([...$video]);
         $question =  Question::create([...$questionData, "video_id" => $video->id]);
        //  dump($question);exit;
         $answers = $this->getConnection()
         ->table("form_answers")
         ->where('question_id', $questionID)
         ->selectRaw("*")
         ->get();
        //  $answers = $answers->take(10);
        //  dump($answers);exit;
        $rawAnswers = $answers->map(function ($answer) use ($question) {
            return $this->buildAnswer($answer, $question->uuid);
        });
        collect($rawAnswers)->each(function ($answer) use ($question) {
            $this->storeAnswer($answer, $question);
        });

    }
    private function storeAnswer(array $answerData, $question)
    {
        if ($answerData['user_id'] !== null) {
            $question->answer()->create($answerData);
        }
    }
//     private function storeAnswer(array $answerData, $question)
// {
//     if ($answerData['user_id'] !== null) {
//         $question->answer()->firstOrCreate(
//             ['question_id' => $question->id], $answerData
//         );
//     }
// }
    private function buildQuestion($question)
    {
        $timestamp = (int)$question->created_at;
        $timestamp = intval($timestamp / 1000);
        $created_at = $this->timeStampConversion($timestamp);

        return [
            'id'   => $question->id,
            'text' => $question->text,
            'is_answerable' => !$question->no_answer,
            'position' => $question->position,
            'created_at' => $created_at,
            'updated_at' => $created_at,
            "video" => [
                        "link" => $question->vimeo_link,
                        "source" =>   VIMEO,
                        "slug"  => "questionnaire_".$question->id,
                        'created_at' => $created_at,
                        'updated_at' => $created_at,
                    ],
        ];
    }
    public function buildAnswer($answer,$questionUuID)
    {
        // dd($answer);
        $timestamp = (int)$answer->created_at;
        $timestamp = intval($timestamp / 1000);
        $created_at = $this->timeStampConversion($timestamp);

        $userID =  $this->getUsers($answer);   
        return [
                // 'uuid' => $questionUuID,
                'user_id' => $userID,
                'text' => $answer->text,
                'watched' => $answer->watched ?? true,
                'created_at' => $created_at,
                'updated_at' => $created_at,
            ];
        
    }

   

    private function getUsers($answer)  {
        $userEmail = $this->getConnection()
                        ->table("users")
                        ->distinct("email")
                        ->where('id',$answer->user_id)
                        ->selectRaw("email")->first();
        if(isset($userEmail)){
            $userN = DB::select('SELECT id FROM users WHERE email = :email', ['email' => $userEmail->email]);

            // dump($userN[0]->id);die;
            if ($userN) {
               return  $userN[0]->id;
            } 
        }
       
        return null;
    }
   

   
}
