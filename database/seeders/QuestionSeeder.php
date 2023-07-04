<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\Video;
use App\Traits\CommonServices;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    use CommonServices;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $questions = array(
            [
                'text' => 'Goal setting & Discovery Process',
                'position' => 1,
                'is_answerable' => false,
                'video_slug' => 'questionnaire_1'
            ],
            [
                'text' => "Colten's Back Story",
                'position' => 2,
                'is_answerable' => false,
                'video_slug' => 'questionnaire_2'
            ],
            [
                'text' => "What is happening right now in your life that has inspired you or challenged you to start your own online business?",
                'position' => 3,
                'is_answerable' => true,
                'video_slug' => 'questionnaire_3'
            ],
            [
                'text' => "Do you have the heart, desire, & willingness to change?",
                'position' => 4,
                'is_answerable' => true,
                'video_slug' => 'questionnaire_4'
            ],
            [
                'text' => "When was your \"line-in-the-sand\" moment? (be descriptive)",
                'position' => 5,
                'is_answerable' => true,
                'video_slug' => 'questionnaire_5'
            ],
            [
                'text' => "Where do you see yourself in 3 years from now? What qualities do you have to change to get there?",
                'position' => 6,
                'is_answerable' => true,
                'video_slug' => 'questionnaire_6'
            ],
            [
                'text' => "Describe a perfect day in your dream life!",
                'position' => 7,
                'is_answerable' => true,
                'video_slug' => 'questionnaire_7'
            ],
            [
                'text' => "What are you passionate about?",
                'position' => 8,
                'is_answerable' => true,
                'video_slug' => 'questionnaire_8'
            ],
            [
                'text' => "What is your \"dream\" monthly income?",
                'position' => 9,
                'is_answerable' => true,
                'video_slug' => 'questionnaire_9'
            ],
            [
                'text' => "How much cash do you have available to invest in your online business?",
                'position' => 10,
                'is_answerable' => true,
                'video_slug' => 'questionnaire_10'
            ],
            [
                'text' => "Who is going to benefit from your success in Race To Freedom other than yourself?",
                'position' => 11,
                'is_answerable' => true,
                'video_slug' => 'questionnaire_11'
            ],
            [
                'text' => "What skills & characteristics are you most excited to share with the community?",
                'position' => 12,
                'is_answerable' => true,
                'video_slug' => 'questionnaire_12'
            ],
            [
                'text' => "You unlocked the 1st training: Product Partnership 101",
                'position' => 13,
                'is_answerable' => false,
                'video_slug' => 'questionnaire_13'
            ]
        );

        $excludedUuid = [];
        foreach ($questions as &$question) {
            $question['uuid'] = $this->generateUniqueUUID(Question::class, excludedUuids: $excludedUuid);
            array_push($excludedUuid, $question['uuid']);

            $question['video_id'] = $this->getQuestionID($question['video_slug']);
            unset($question['video_slug']);
        }

        Question::insert($questions);

    }

    protected function getQuestionID($slug)
    {
        $video = Video::findBySlug($slug);
        return $video->id;
    }
}
