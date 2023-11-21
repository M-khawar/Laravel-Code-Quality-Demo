<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\Video;
use App\Traits\CommonServices;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VideoSeeder extends Seeder
{
    use CommonServices;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $videos = array(
            [
                "slug" => "webinar_video",
                "link" => "9jsz02qcml",
                "source" => WISTIA,
            ],
            [
                "slug" => "welcome_video",
                "link" => "562275678",
                "source" => VIMEO,
            ],
            [
                "slug" => "promote_video",
                "link" => "562275356",
                "source" => VIMEO,
            ],
            /*
            [
                "slug" => "questionnaire_1",
                "link" => "545694152",
                "source" => VIMEO,
            ],
            [
                "slug" => "questionnaire_2",
                "link" => "539746831",
                "source" => VIMEO,
            ],
            [
                "slug" => "questionnaire_3",
                "link" => "540709260",
                "source" => VIMEO,
            ],
            [
                "slug" => "questionnaire_4",
                "link" => "539759622",
                "source" => VIMEO,
            ],
            [
                "slug" => "questionnaire_5",
                "link" => "539760306",
                "source" => VIMEO,
            ],
            [
                "slug" => "questionnaire_6",
                "link" => "539763864",
                "source" => VIMEO,
            ],
            [
                "slug" => "questionnaire_7",
                "link" => "540709910",
                "source" => VIMEO,
            ],
            [
                "slug" => "questionnaire_8",
                "link" => "539761178",
                "source" => VIMEO,
            ],
            [
                "slug" => "questionnaire_9",
                "link" => "539745669",
                "source" => VIMEO,
            ],
            [
                "slug" => "questionnaire_10",
                "link" => "539761705",
                "source" => VIMEO,
            ],
            [
                "slug" => "questionnaire_11",
                "link" => "539762658",
                "source" => VIMEO,
            ],
            [
                "slug" => "questionnaire_12",
                "link" => "539763308",
                "source" => VIMEO,
            ],
            [
                "slug" => "questionnaire_13",
                "link" => "540274130",
                "source" => VIMEO,
            ]
            */
        );

        foreach ($videos as &$video) {
            $video['created_at'] = now();
            $video['updated_at'] = now();
            $video['uuid'] = $this->generateUniqueUUID(Question::class);
        }

        Video::insert($videos);
    }
}
