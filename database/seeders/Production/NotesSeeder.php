<?php

namespace Database\Seeders\Production;

use App\Models\CompletedLesson;
use App\Models\Course;
use Illuminate\Support\Facades\DB;
use App\Models\Video;
use App\Models\CourseSection;
use App\Models\Note;
use App\Models\Question;
use App\Models\User;
use Database\Seeders\Traits\DisableForeignKeys;
use Database\Seeders\Traits\TruncateTable;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Carbon;

class NotesSeeder extends ConfigureDatabase
{
    // private $roleModel;
    use DisableForeignKeys, TruncateTable;

    public function run()
    {
        $this->disableForeignKeys();
        $this->truncateMultiple(["notes"]);

        $notes = $this->getConnection()
                        ->table("form_notes")
                        ->selectRaw("*")
                        ->get();

    //    $questions = $questions->take(10);
        $rawnotes = $notes->map(function ($note) {
            return $this->buildNote($note);
        });
        collect($rawnotes)->each(function ($note) {
            $this->storeNote($note);
        });

        $this->enableForeignKeys();
    }
    private function buildNote($note)
    {
        $timestamp = (int)$note->created_at;
        $timestamp = intval($timestamp / 1000);
        $created_at = $this->timeStampConversion($timestamp);

        $userID =  $this->getUsers($note);   
            return [
                'user_id' => $userID,
                'note' => $note->note,
                'created_at' => $created_at,
                'updated_at' => $created_at
            ];
    }


    private function storeNote(array $noteData)
    {
        
        Note::create([...$noteData]);
        
    }

    private function getUsers($note)  {
        $userEmail = $this->getConnection()
                        ->table("users")
                        ->distinct("email")
                        ->where('id',$note->user_id)
                        ->selectRaw("email")->first();

        // $userN = User::where('email', $userEmail->email)->first();
        $userN = DB::select('SELECT id FROM users WHERE email = :email', ['email' => $userEmail->email]);

        // dump($userN[0]->id);die;
        if ($userN) {
           return  $userN[0]->id;
        } 
        return 0;
    }
   

   
}
