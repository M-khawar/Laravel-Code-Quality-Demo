<?php

namespace Database\Seeders;

use App\Models\Question;
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
                'vimeo_link' => "545694152",
                'is_answerable' => false,
            ],
            [
                'text' => "Colten's Back Story",
                'position' => 2,
                'vimeo_link' => "539746831",
                'is_answerable' => false,
            ],
            [
                'text' => "What is happening right now in your life that has inspired you or challenged you to start your own online business?",
                'position' => 3,
                'vimeo_link' => "540709260",
                'is_answerable' => true,
            ],
            [
                'text' => "Do you have the heart, desire, & willingness to change?",
                'position' => 4,
                'vimeo_link' => "539759622",
                'is_answerable' => true,
            ],
            [
                'text' => "When was your \"line-in-the-sand\" moment? (be descriptive)",
                'position' => 5,
                'vimeo_link' => "539760306",
                'is_answerable' => true,
            ],
            [
                'text' => "Where do you see yourself in 3 years from now? What qualities do you have to change to get there?",
                'position' => 6,
                'vimeo_link' => "539763864",
                'is_answerable' => true,
            ],
            [
                'text' => "Describe a perfect day in your dream life!",
                'position' => 7,
                'vimeo_link' => "540709910",
                'is_answerable' => true,
            ],
            [
                'text' => "What are you passionate about?",
                'position' => 8,
                'vimeo_link' => "539761178",
                'is_answerable' => true,
            ],
            [
                'text' => "What is your \"dream\" monthly income?",
                'position' => 9,
                'vimeo_link' => "539745669",
                'is_answerable' => true,
            ],
            [
                'text' => "How much cash do you have available to invest in your online business?",
                'position' => 10,
                'vimeo_link' => "539761705",
                'is_answerable' => true,
            ],
            [
                'text' => "Who is going to benefit from your success in Race To Freedom other than yourself?",
                'position' => 11,
                'vimeo_link' => "539762658",
                'is_answerable' => true,
            ],
            [
                'text' => "What skills & characteristics are you most excited to share with the community?",
                'position' => 12,
                'vimeo_link' => "539763308",
                'is_answerable' => true,
            ],
            [
                'text' => "You unlocked the 1st training: Product Partnership 101",
                'position' => 13,
                'vimeo_link' => "540274130",
                'is_answerable' => false,
            ]
        );

        $excludedUuid = [];
        foreach ($questions as &$question) {
            $question['uuid'] = $this->generateUniqueUUID(Question::class, excludedUuids: $excludedUuid);
            array_push($excludedUuid, $question['uuid']);
        }

        Question::insert($questions);

    }
}
