<?php

namespace Database\Seeders\DummyData;

use App\Models\{Course, Media, Video};
use Database\Seeders\Traits\{DisableForeignKeys, TruncateTable};
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    use DisableForeignKeys, TruncateTable;

    private $roleModel;
    private $mediaModel;

    public function __construct()
    {
        $this->roleModel = app(config('permission.models.role'));
        $this->mediaModel = app(Media::class);
    }

    public function run()
    {
        $this->disableForeignKeys();
        $this->truncateMultiple(["courses", "course_sections", "course_lessons", "completed_lessons", "has_course_permissions"]);
        $this->enableForeignKeys();

        $courses = [
            [
                "name" => "LIVE Recorded Closing Calls ",
                "description" => "Learn from the best with real life examples",
                "thumbnail" => "https://docs-prod.nyc3.digitaloceanspaces.com/Screen%20Shot%202021-03-03%20at%202.38.35%20PM.png",
                "roles" => [TRIFECTA_ROLE],
                "sections" => [
                    [
                        "name" => "Live Closing Calls", "description" => null,
                        //fake
                        "lessons" => [
                            [
                                "name" => "Promoting Events",
                                "description" => "with Colten Echave",
                                "resources" => null,
                                "video" => ["link" => "81lgyj312d", "source" => WISTIA],
                            ],
                            [
                                "name" => "public Speaking",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563004912, "source" => VIMEO],
                            ],
                            [
                                "name" => "Direct Sales",
                                "description" => "with Ben Landry",
                                "resources" => null,
                                "video" => ["link" => 563003350, "source" => VIMEO],

                            ],
                            [
                                "name" => "Professional Networking",
                                "description" => "with Jennifer McCormick",
                                "resources" => null,
                                "video" => ["link" => 563003789, "source" => VIMEO],
                            ],
                            [
                                "name" => "Closing The Sale",
                                "description" => "with Priscilla Echave",
                                "resources" => null,
                                "video" => ["link" => 563002931, "source" => VIMEO],
                            ],
                            [
                                "name" => "Team Building",
                                "description" => "with Ryan Bell",
                                "resources" => null,
                                "video" => ["link" => 563005622, "source" => VIMEO],
                            ],
                            [
                                "name" => "Rising Star Panel",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563005272, "source" => VIMEO],
                            ],
                        ],
                    ],
                ]
            ],
            [
                "name" => "Wednesday Night Distributor Trainings",
                "description" => null,
                "thumbnail" => "https://docs-prod.nyc3.digitaloceanspaces.com/download-3.jpg",
                "roles" => [ENAGIC_ROLE],
                "sections" => [
                    [
                        "name" => "2022", "description" => null,
                        //fake
                        "lessons" => [
                            [
                                "name" => "Promoting Events",
                                "description" => "with Colten Echave",
                                "resources" => null,
                                "video" => ["link" => "81lgyj312d", "source" => WISTIA],
                            ],
                            [
                                "name" => "public Speaking",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563004912, "source" => VIMEO],
                            ],
                            [
                                "name" => "Direct Sales",
                                "description" => "with Ben Landry",
                                "resources" => null,
                                "video" => ["link" => 563003350, "source" => VIMEO],

                            ],
                            [
                                "name" => "Professional Networking",
                                "description" => "with Jennifer McCormick",
                                "resources" => null,
                                "video" => ["link" => 563003789, "source" => VIMEO],
                            ],
                            [
                                "name" => "Closing The Sale",
                                "description" => "with Priscilla Echave",
                                "resources" => null,
                                "video" => ["link" => 563002931, "source" => VIMEO],
                            ],
                            [
                                "name" => "Team Building",
                                "description" => "with Ryan Bell",
                                "resources" => null,
                                "video" => ["link" => 563005622, "source" => VIMEO],
                            ],
                            [
                                "name" => "Rising Star Panel",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563005272, "source" => VIMEO],
                            ],
                        ],
                    ],
                    [
                        "name" => "2023", "description" => null,
                        //fake
                        "lessons" => [
                            [
                                "name" => "Promoting Events",
                                "description" => "with Colten Echave",
                                "resources" => null,
                                "video" => ["link" => "81lgyj312d", "source" => WISTIA],
                            ],
                            [
                                "name" => "public Speaking",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563004912, "source" => VIMEO],
                            ],
                            [
                                "name" => "Direct Sales",
                                "description" => "with Ben Landry",
                                "resources" => null,
                                "video" => ["link" => 563003350, "source" => VIMEO],

                            ],
                            [
                                "name" => "Professional Networking",
                                "description" => "with Jennifer McCormick",
                                "resources" => null,
                                "video" => ["link" => 563003789, "source" => VIMEO],
                            ],
                            [
                                "name" => "Closing The Sale",
                                "description" => "with Priscilla Echave",
                                "resources" => null,
                                "video" => ["link" => 563002931, "source" => VIMEO],
                            ],
                            [
                                "name" => "Team Building",
                                "description" => "with Ryan Bell",
                                "resources" => null,
                                "video" => ["link" => 563005622, "source" => VIMEO],
                            ],
                            [
                                "name" => "Rising Star Panel",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563005272, "source" => VIMEO],
                            ],
                        ],
                    ],
                ]
            ],
            [
                "name" => "30 Day Roadmap",
                "description" => "You Suddenly Lose Everything... What Would You do From Day 1 To Day 30 To Save Yourself!",
                "thumbnail" => "https://docs-prod.nyc3.digitaloceanspaces.com/30%20Day%20Roadmap%20Training%20box.png",
                "roles" => [TRIFECTA_ROLE],
                "sections" => [
                    [
                        "name" => "Day 1 - Day 30", "description" => null,
                        //fake
                        "lessons" => [
                            [
                                "name" => "Promoting Events",
                                "description" => "with Colten Echave",
                                "resources" => null,
                                "video" => ["link" => "81lgyj312d", "source" => WISTIA],
                            ],
                            [
                                "name" => "public Speaking",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563004912, "source" => VIMEO],
                            ],
                            [
                                "name" => "Direct Sales",
                                "description" => "with Ben Landry",
                                "resources" => null,
                                "video" => ["link" => 563003350, "source" => VIMEO],

                            ],
                            [
                                "name" => "Professional Networking",
                                "description" => "with Jennifer McCormick",
                                "resources" => null,
                                "video" => ["link" => 563003789, "source" => VIMEO],
                            ],
                            [
                                "name" => "Closing The Sale",
                                "description" => "with Priscilla Echave",
                                "resources" => null,
                                "video" => ["link" => 563002931, "source" => VIMEO],
                            ],
                            [
                                "name" => "Team Building",
                                "description" => "with Ryan Bell",
                                "resources" => null,
                                "video" => ["link" => 563005622, "source" => VIMEO],
                            ],
                            [
                                "name" => "Rising Star Panel",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563005272, "source" => VIMEO],
                            ],
                        ],
                    ],
                    [
                        "name" => "Direct Sales Guide", "description" => null,
                        //fake
                        "lessons" => [
                            [
                                "name" => "Promoting Events",
                                "description" => "with Colten Echave",
                                "resources" => null,
                                "video" => ["link" => "81lgyj312d", "source" => WISTIA],
                            ],
                            [
                                "name" => "public Speaking",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563004912, "source" => VIMEO],
                            ],
                            [
                                "name" => "Direct Sales",
                                "description" => "with Ben Landry",
                                "resources" => null,
                                "video" => ["link" => 563003350, "source" => VIMEO],

                            ],
                            [
                                "name" => "Professional Networking",
                                "description" => "with Jennifer McCormick",
                                "resources" => null,
                                "video" => ["link" => 563003789, "source" => VIMEO],
                            ],
                            [
                                "name" => "Closing The Sale",
                                "description" => "with Priscilla Echave",
                                "resources" => null,
                                "video" => ["link" => 563002931, "source" => VIMEO],
                            ],
                            [
                                "name" => "Team Building",
                                "description" => "with Ryan Bell",
                                "resources" => null,
                                "video" => ["link" => 563005622, "source" => VIMEO],
                            ],
                            [
                                "name" => "Rising Star Panel",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563005272, "source" => VIMEO],
                            ],
                        ],
                    ],
                ]
            ],
            [
                "name" => "LIVE EVENT RECORDINGS",
                "description" => "Learn all the best insider secrets without having to pay for a plane ticket & hotel room",
                "thumbnail" => "https://r2f.sfo2.digitaloceanspaces.com/2%20Live%20Event%20Recordings%20ipad1.png",
                "roles" => [TRIFECTA_ROLE],
                "sections" => [
                    [
                        "name" => "R2F Experience 2019",
                        "description" => null,
                        "lessons" => [
                            [
                                "name" => "Promoting Events",
                                "description" => "with Colten Echave",
                                "resources" => null,
                                "video" => ["link" => "81lgyj312d", "source" => WISTIA],
                            ],
                            [
                                "name" => "public Speaking",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563004912, "source" => VIMEO],
                            ],
                            [
                                "name" => "Direct Sales",
                                "description" => "with Ben Landry",
                                "resources" => null,
                                "video" => ["link" => 563003350, "source" => VIMEO],

                            ],
                            [
                                "name" => "Professional Networking",
                                "description" => "with Jennifer McCormick",
                                "resources" => null,
                                "video" => ["link" => 563003789, "source" => VIMEO],
                            ],
                            [
                                "name" => "Closing The Sale",
                                "description" => "with Priscilla Echave",
                                "resources" => null,
                                "video" => ["link" => 563002931, "source" => VIMEO],
                            ],
                            [
                                "name" => "Team Building",
                                "description" => "with Ryan Bell",
                                "resources" => null,
                                "video" => ["link" => 563005622, "source" => VIMEO],
                            ],
                            [
                                "name" => "Rising Star Panel",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563005272, "source" => VIMEO],
                            ],
                        ]
                    ],
                    [
                        "name" => "R2F Experience 2020",
                        "description" => "Learn From The Top 6 - Figure Earners At Our LIVE Event!",
                        "lessons" => [
                            [
                                "name" => "Getting People Started",
                                "description" => "Getting People Started Starts at 5:56",
                                "resources" => null,
                                "video" => ["link" => 563008690, "source" => VIMEO],
                            ],
                            [
                                "name" => "Health Testimonials",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563023938, "source" => VIMEO],
                            ],
                            [
                                "name" => "Priscilla & Ryan",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563024212, "source" => VIMEO],
                            ],
                            [
                                "name" => "Rising Star Panel",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563024548, "source" => VIMEO],
                            ],
                            [
                                "name" => "Tam & Giordano",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563024861, "source" => VIMEO],
                            ],
                        ]
                    ],
                    [
                        "name" => "R2F Experience Jan 2022",
                        "description" => null,
                        //fake
                        "lessons" => [
                            [
                                "name" => "Promoting Events",
                                "description" => "with Colten Echave",
                                "resources" => null,
                                "video" => ["link" => "81lgyj312d", "source" => WISTIA],
                            ],
                            [
                                "name" => "public Speaking",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563004912, "source" => VIMEO],
                            ],
                            [
                                "name" => "Direct Sales",
                                "description" => "with Ben Landry",
                                "resources" => null,
                                "video" => ["link" => 563003350, "source" => VIMEO],

                            ],
                            [
                                "name" => "Professional Networking",
                                "description" => "with Jennifer McCormick",
                                "resources" => null,
                                "video" => ["link" => 563003789, "source" => VIMEO],
                            ],
                            [
                                "name" => "Closing The Sale",
                                "description" => "with Priscilla Echave",
                                "resources" => null,
                                "video" => ["link" => 563002931, "source" => VIMEO],
                            ],
                            [
                                "name" => "Team Building",
                                "description" => "with Ryan Bell",
                                "resources" => null,
                                "video" => ["link" => 563005622, "source" => VIMEO],
                            ],
                            [
                                "name" => "Rising Star Panel",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563005272, "source" => VIMEO],
                            ],
                        ],
                    ],
                    [
                        "name" => "R2F Experience May 2022",
                        "description" => null,
                        //fake
                        "lessons" => [
                            [
                                "name" => "Promoting Events",
                                "description" => "with Colten Echave",
                                "resources" => null,
                                "video" => ["link" => "81lgyj312d", "source" => WISTIA],
                            ],
                            [
                                "name" => "public Speaking",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563004912, "source" => VIMEO],
                            ],
                            [
                                "name" => "Direct Sales",
                                "description" => "with Ben Landry",
                                "resources" => null,
                                "video" => ["link" => 563003350, "source" => VIMEO],

                            ],
                            [
                                "name" => "Professional Networking",
                                "description" => "with Jennifer McCormick",
                                "resources" => null,
                                "video" => ["link" => 563003789, "source" => VIMEO],
                            ],
                            [
                                "name" => "Closing The Sale",
                                "description" => "with Priscilla Echave",
                                "resources" => null,
                                "video" => ["link" => 563002931, "source" => VIMEO],
                            ],
                            [
                                "name" => "Team Building",
                                "description" => "with Ryan Bell",
                                "resources" => null,
                                "video" => ["link" => 563005622, "source" => VIMEO],
                            ],
                            [
                                "name" => "Rising Star Panel",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563005272, "source" => VIMEO],
                            ],
                        ],
                    ],
                    [
                        "name" => "R2F Experience Oct . 2022",
                        "description" => null,
                        //fake
                        "lessons" => [
                            [
                                "name" => "Promoting Events",
                                "description" => "with Colten Echave",
                                "resources" => null,
                                "video" => ["link" => "81lgyj312d", "source" => WISTIA],
                            ],
                            [
                                "name" => "public Speaking",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563004912, "source" => VIMEO],
                            ],
                            [
                                "name" => "Direct Sales",
                                "description" => "with Ben Landry",
                                "resources" => null,
                                "video" => ["link" => 563003350, "source" => VIMEO],

                            ],
                            [
                                "name" => "Professional Networking",
                                "description" => "with Jennifer McCormick",
                                "resources" => null,
                                "video" => ["link" => 563003789, "source" => VIMEO],
                            ],
                            [
                                "name" => "Closing The Sale",
                                "description" => "with Priscilla Echave",
                                "resources" => null,
                                "video" => ["link" => 563002931, "source" => VIMEO],
                            ],
                            [
                                "name" => "Team Building",
                                "description" => "with Ryan Bell",
                                "resources" => null,
                                "video" => ["link" => 563005622, "source" => VIMEO],
                            ],
                            [
                                "name" => "Rising Star Panel",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563005272, "source" => VIMEO],
                            ],
                        ],
                    ],
                ],
            ],
            [
                "name" => "Bootcamp Replay",
                "description" => null,
                "thumbnail" => "https://docs-prod.nyc3.digitaloceanspaces.com/Bootcamp.jpeg",
                "roles" => [ENAGIC_ROLE],
                "sections" => [
                    [
                        "name" => "Dec. 2022", "description" => null,
                        //fake
                        "lessons" => [
                            [
                                "name" => "Promoting Events",
                                "description" => "with Colten Echave",
                                "resources" => null,
                                "video" => ["link" => "81lgyj312d", "source" => WISTIA],
                            ],
                            [
                                "name" => "public Speaking",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563004912, "source" => VIMEO],
                            ],
                            [
                                "name" => "Direct Sales",
                                "description" => "with Ben Landry",
                                "resources" => null,
                                "video" => ["link" => 563003350, "source" => VIMEO],

                            ],
                            [
                                "name" => "Professional Networking",
                                "description" => "with Jennifer McCormick",
                                "resources" => null,
                                "video" => ["link" => 563003789, "source" => VIMEO],
                            ],
                            [
                                "name" => "Closing The Sale",
                                "description" => "with Priscilla Echave",
                                "resources" => null,
                                "video" => ["link" => 563002931, "source" => VIMEO],
                            ],
                            [
                                "name" => "Team Building",
                                "description" => "with Ryan Bell",
                                "resources" => null,
                                "video" => ["link" => 563005622, "source" => VIMEO],
                            ],
                            [
                                "name" => "Rising Star Panel",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563005272, "source" => VIMEO],
                            ],
                        ],
                    ],
                    [
                        "name" => "Jan. 2023", "description" => null,
                        //fake
                        "lessons" => [
                            [
                                "name" => "Promoting Events",
                                "description" => "with Colten Echave",
                                "resources" => null,
                                "video" => ["link" => "81lgyj312d", "source" => WISTIA],
                            ],
                            [
                                "name" => "public Speaking",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563004912, "source" => VIMEO],
                            ],
                            [
                                "name" => "Direct Sales",
                                "description" => "with Ben Landry",
                                "resources" => null,
                                "video" => ["link" => 563003350, "source" => VIMEO],

                            ],
                            [
                                "name" => "Professional Networking",
                                "description" => "with Jennifer McCormick",
                                "resources" => null,
                                "video" => ["link" => 563003789, "source" => VIMEO],
                            ],
                            [
                                "name" => "Closing The Sale",
                                "description" => "with Priscilla Echave",
                                "resources" => null,
                                "video" => ["link" => 563002931, "source" => VIMEO],
                            ],
                            [
                                "name" => "Team Building",
                                "description" => "with Ryan Bell",
                                "resources" => null,
                                "video" => ["link" => 563005622, "source" => VIMEO],
                            ],
                            [
                                "name" => "Rising Star Panel",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563005272, "source" => VIMEO],
                            ],
                        ],
                    ],
                ]
            ],
            [
                "name" => "Power Huddle Replay",
                "description" => null,
                "thumbnail" => "https://docs-prod.nyc3.digitaloceanspaces.com/Huddle.jpeg",
                "roles" => [ENAGIC_ROLE],
                "sections" => [
                    [
                        "name" => "Dec. 2022", "description" => null,
                        //fake
                        "lessons" => [
                            [
                                "name" => "Promoting Events",
                                "description" => "with Colten Echave",
                                "resources" => null,
                                "video" => ["link" => "81lgyj312d", "source" => WISTIA],
                            ],
                            [
                                "name" => "public Speaking",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563004912, "source" => VIMEO],
                            ],
                            [
                                "name" => "Direct Sales",
                                "description" => "with Ben Landry",
                                "resources" => null,
                                "video" => ["link" => 563003350, "source" => VIMEO],

                            ],
                            [
                                "name" => "Professional Networking",
                                "description" => "with Jennifer McCormick",
                                "resources" => null,
                                "video" => ["link" => 563003789, "source" => VIMEO],
                            ],
                            [
                                "name" => "Closing The Sale",
                                "description" => "with Priscilla Echave",
                                "resources" => null,
                                "video" => ["link" => 563002931, "source" => VIMEO],
                            ],
                            [
                                "name" => "Team Building",
                                "description" => "with Ryan Bell",
                                "resources" => null,
                                "video" => ["link" => 563005622, "source" => VIMEO],
                            ],
                            [
                                "name" => "Rising Star Panel",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563005272, "source" => VIMEO],
                            ],
                        ],
                    ],
                    [
                        "name" => "Jan. 2023", "description" => null,
                        //fake
                        "lessons" => [
                            [
                                "name" => "Promoting Events",
                                "description" => "with Colten Echave",
                                "resources" => null,
                                "video" => ["link" => "81lgyj312d", "source" => WISTIA],
                            ],
                            [
                                "name" => "public Speaking",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563004912, "source" => VIMEO],
                            ],
                            [
                                "name" => "Direct Sales",
                                "description" => "with Ben Landry",
                                "resources" => null,
                                "video" => ["link" => 563003350, "source" => VIMEO],

                            ],
                            [
                                "name" => "Professional Networking",
                                "description" => "with Jennifer McCormick",
                                "resources" => null,
                                "video" => ["link" => 563003789, "source" => VIMEO],
                            ],
                            [
                                "name" => "Closing The Sale",
                                "description" => "with Priscilla Echave",
                                "resources" => null,
                                "video" => ["link" => 563002931, "source" => VIMEO],
                            ],
                            [
                                "name" => "Team Building",
                                "description" => "with Ryan Bell",
                                "resources" => null,
                                "video" => ["link" => 563005622, "source" => VIMEO],
                            ],
                            [
                                "name" => "Rising Star Panel",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563005272, "source" => VIMEO],
                            ],
                        ],
                    ],
                ]
            ],
            [
                "name" => "TikTok Master class",
                "description" => "Learn the top secrets tips & tricks to master TikTok & impact lives!",
                "thumbnail" => "https://docs-prod.nyc3.digitaloceanspaces.com/TIKTOK.png",
                "roles" => [ENAGIC_ROLE],
                "sections" => [
                    [
                        "name" => "TikTok", "description" => null,
                        //fake
                        "lessons" => [
                            [
                                "name" => "Promoting Events",
                                "description" => "with Colten Echave",
                                "resources" => null,
                                "video" => ["link" => "81lgyj312d", "source" => WISTIA],
                            ],
                            [
                                "name" => "public Speaking",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563004912, "source" => VIMEO],
                            ],
                            [
                                "name" => "Direct Sales",
                                "description" => "with Ben Landry",
                                "resources" => null,
                                "video" => ["link" => 563003350, "source" => VIMEO],

                            ],
                            [
                                "name" => "Professional Networking",
                                "description" => "with Jennifer McCormick",
                                "resources" => null,
                                "video" => ["link" => 563003789, "source" => VIMEO],
                            ],
                            [
                                "name" => "Closing The Sale",
                                "description" => "with Priscilla Echave",
                                "resources" => null,
                                "video" => ["link" => 563002931, "source" => VIMEO],
                            ],
                            [
                                "name" => "Team Building",
                                "description" => "with Ryan Bell",
                                "resources" => null,
                                "video" => ["link" => 563005622, "source" => VIMEO],
                            ],
                            [
                                "name" => "Rising Star Panel",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563005272, "source" => VIMEO],
                            ],
                        ],
                    ],
                ]
            ],
            [
                "name" => "Product Overview",
                "description" => null,
                "thumbnail" => "https://docs-prod.nyc3.digitaloceanspaces.com/Enagics%20Trifecta%20Package%20bag.png",
                "roles" => [ENAGIC_ROLE],
                "sections" => [
                    [
                        "name" => "K8", "description" => null,
                        //fake
                        "lessons" => [
                            [
                                "name" => "Promoting Events",
                                "description" => "with Colten Echave",
                                "resources" => null,
                                "video" => ["link" => "81lgyj312d", "source" => WISTIA],
                            ],
                            [
                                "name" => "public Speaking",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563004912, "source" => VIMEO],
                            ],
                            [
                                "name" => "Direct Sales",
                                "description" => "with Ben Landry",
                                "resources" => null,
                                "video" => ["link" => 563003350, "source" => VIMEO],

                            ],
                            [
                                "name" => "Professional Networking",
                                "description" => "with Jennifer McCormick",
                                "resources" => null,
                                "video" => ["link" => 563003789, "source" => VIMEO],
                            ],
                            [
                                "name" => "Closing The Sale",
                                "description" => "with Priscilla Echave",
                                "resources" => null,
                                "video" => ["link" => 563002931, "source" => VIMEO],
                            ],
                            [
                                "name" => "Team Building",
                                "description" => "with Ryan Bell",
                                "resources" => null,
                                "video" => ["link" => 563005622, "source" => VIMEO],
                            ],
                            [
                                "name" => "Rising Star Panel",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563005272, "source" => VIMEO],
                            ],
                        ],
                    ],
                    [
                        "name" => "Anespa", "description" => null,
                        //fake
                        "lessons" => [
                            [
                                "name" => "Promoting Events",
                                "description" => "with Colten Echave",
                                "resources" => null,
                                "video" => ["link" => "81lgyj312d", "source" => WISTIA],
                            ],
                            [
                                "name" => "public Speaking",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563004912, "source" => VIMEO],
                            ],
                            [
                                "name" => "Direct Sales",
                                "description" => "with Ben Landry",
                                "resources" => null,
                                "video" => ["link" => 563003350, "source" => VIMEO],

                            ],
                            [
                                "name" => "Professional Networking",
                                "description" => "with Jennifer McCormick",
                                "resources" => null,
                                "video" => ["link" => 563003789, "source" => VIMEO],
                            ],
                            [
                                "name" => "Closing The Sale",
                                "description" => "with Priscilla Echave",
                                "resources" => null,
                                "video" => ["link" => 563002931, "source" => VIMEO],
                            ],
                            [
                                "name" => "Team Building",
                                "description" => "with Ryan Bell",
                                "resources" => null,
                                "video" => ["link" => 563005622, "source" => VIMEO],
                            ],
                            [
                                "name" => "Rising Star Panel",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563005272, "source" => VIMEO],
                            ],
                        ],
                    ],
                ]
            ],
            [
                "name" => "Your First 90 Days",
                "description" => null,
                "thumbnail" => "https://docs-prod.nyc3.digitaloceanspaces.com/start.jpg",
                "roles" => [ENAGIC_ROLE],
                "sections" => [
                    [
                        "name" => "Getting new People Started", "description" => null,
                        //fake
                        "lessons" => [
                            [
                                "name" => "Promoting Events",
                                "description" => "with Colten Echave",
                                "resources" => null,
                                "video" => ["link" => "81lgyj312d", "source" => WISTIA],
                            ],
                            [
                                "name" => "public Speaking",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563004912, "source" => VIMEO],
                            ],
                            [
                                "name" => "Direct Sales",
                                "description" => "with Ben Landry",
                                "resources" => null,
                                "video" => ["link" => 563003350, "source" => VIMEO],

                            ],
                            [
                                "name" => "Professional Networking",
                                "description" => "with Jennifer McCormick",
                                "resources" => null,
                                "video" => ["link" => 563003789, "source" => VIMEO],
                            ],
                            [
                                "name" => "Closing The Sale",
                                "description" => "with Priscilla Echave",
                                "resources" => null,
                                "video" => ["link" => 563002931, "source" => VIMEO],
                            ],
                            [
                                "name" => "Team Building",
                                "description" => "with Ryan Bell",
                                "resources" => null,
                                "video" => ["link" => 563005622, "source" => VIMEO],
                            ],
                            [
                                "name" => "Rising Star Panel",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563005272, "source" => VIMEO],
                            ],
                        ],
                    ],
                    [
                        "name" => "System", "description" => null,
                        //fake
                        "lessons" => [
                            [
                                "name" => "Promoting Events",
                                "description" => "with Colten Echave",
                                "resources" => null,
                                "video" => ["link" => "81lgyj312d", "source" => WISTIA],
                            ],
                            [
                                "name" => "public Speaking",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563004912, "source" => VIMEO],
                            ],
                            [
                                "name" => "Direct Sales",
                                "description" => "with Ben Landry",
                                "resources" => null,
                                "video" => ["link" => 563003350, "source" => VIMEO],

                            ],
                            [
                                "name" => "Professional Networking",
                                "description" => "with Jennifer McCormick",
                                "resources" => null,
                                "video" => ["link" => 563003789, "source" => VIMEO],
                            ],
                            [
                                "name" => "Closing The Sale",
                                "description" => "with Priscilla Echave",
                                "resources" => null,
                                "video" => ["link" => 563002931, "source" => VIMEO],
                            ],
                            [
                                "name" => "Team Building",
                                "description" => "with Ryan Bell",
                                "resources" => null,
                                "video" => ["link" => 563005622, "source" => VIMEO],
                            ],
                            [
                                "name" => "Rising Star Panel",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563005272, "source" => VIMEO],
                            ],
                        ],
                    ],
                    [
                        "name" => "Social Media", "description" => null,
                        //fake
                        "lessons" => [
                            [
                                "name" => "Promoting Events",
                                "description" => "with Colten Echave",
                                "resources" => null,
                                "video" => ["link" => "81lgyj312d", "source" => WISTIA],
                            ],
                            [
                                "name" => "public Speaking",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563004912, "source" => VIMEO],
                            ],
                            [
                                "name" => "Direct Sales",
                                "description" => "with Ben Landry",
                                "resources" => null,
                                "video" => ["link" => 563003350, "source" => VIMEO],

                            ],
                            [
                                "name" => "Professional Networking",
                                "description" => "with Jennifer McCormick",
                                "resources" => null,
                                "video" => ["link" => 563003789, "source" => VIMEO],
                            ],
                            [
                                "name" => "Closing The Sale",
                                "description" => "with Priscilla Echave",
                                "resources" => null,
                                "video" => ["link" => 563002931, "source" => VIMEO],
                            ],
                            [
                                "name" => "Team Building",
                                "description" => "with Ryan Bell",
                                "resources" => null,
                                "video" => ["link" => 563005622, "source" => VIMEO],
                            ],
                            [
                                "name" => "Rising Star Panel",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563005272, "source" => VIMEO],
                            ],
                        ],
                    ],
                    [
                        "name" => "List Organization", "description" => null,
                        //fake
                        "lessons" => [
                            [
                                "name" => "Promoting Events",
                                "description" => "with Colten Echave",
                                "resources" => null,
                                "video" => ["link" => "81lgyj312d", "source" => WISTIA],
                            ],
                            [
                                "name" => "public Speaking",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563004912, "source" => VIMEO],
                            ],
                            [
                                "name" => "Direct Sales",
                                "description" => "with Ben Landry",
                                "resources" => null,
                                "video" => ["link" => 563003350, "source" => VIMEO],

                            ],
                            [
                                "name" => "Professional Networking",
                                "description" => "with Jennifer McCormick",
                                "resources" => null,
                                "video" => ["link" => 563003789, "source" => VIMEO],
                            ],
                            [
                                "name" => "Closing The Sale",
                                "description" => "with Priscilla Echave",
                                "resources" => null,
                                "video" => ["link" => 563002931, "source" => VIMEO],
                            ],
                            [
                                "name" => "Team Building",
                                "description" => "with Ryan Bell",
                                "resources" => null,
                                "video" => ["link" => 563005622, "source" => VIMEO],
                            ],
                            [
                                "name" => "Rising Star Panel",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563005272, "source" => VIMEO],
                            ],
                        ],
                    ],
                    [
                        "name" => "How To Fill Process Sales", "description" => null,
                        //fake
                        "lessons" => [
                            [
                                "name" => "Promoting Events",
                                "description" => "with Colten Echave",
                                "resources" => null,
                                "video" => ["link" => "81lgyj312d", "source" => WISTIA],
                            ],
                            [
                                "name" => "public Speaking",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563004912, "source" => VIMEO],
                            ],
                            [
                                "name" => "Direct Sales",
                                "description" => "with Ben Landry",
                                "resources" => null,
                                "video" => ["link" => 563003350, "source" => VIMEO],

                            ],
                            [
                                "name" => "Professional Networking",
                                "description" => "with Jennifer McCormick",
                                "resources" => null,
                                "video" => ["link" => 563003789, "source" => VIMEO],
                            ],
                            [
                                "name" => "Closing The Sale",
                                "description" => "with Priscilla Echave",
                                "resources" => null,
                                "video" => ["link" => 563002931, "source" => VIMEO],
                            ],
                            [
                                "name" => "Team Building",
                                "description" => "with Ryan Bell",
                                "resources" => null,
                                "video" => ["link" => 563005622, "source" => VIMEO],
                            ],
                            [
                                "name" => "Rising Star Panel",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563005272, "source" => VIMEO],
                            ],
                        ],
                    ],
                    [
                        "name" => "Closing", "description" => null,
                        //fake
                        "lessons" => [
                            [
                                "name" => "Promoting Events",
                                "description" => "with Colten Echave",
                                "resources" => null,
                                "video" => ["link" => "81lgyj312d", "source" => WISTIA],
                            ],
                            [
                                "name" => "public Speaking",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563004912, "source" => VIMEO],
                            ],
                            [
                                "name" => "Direct Sales",
                                "description" => "with Ben Landry",
                                "resources" => null,
                                "video" => ["link" => 563003350, "source" => VIMEO],

                            ],
                            [
                                "name" => "Professional Networking",
                                "description" => "with Jennifer McCormick",
                                "resources" => null,
                                "video" => ["link" => 563003789, "source" => VIMEO],
                            ],
                            [
                                "name" => "Closing The Sale",
                                "description" => "with Priscilla Echave",
                                "resources" => null,
                                "video" => ["link" => 563002931, "source" => VIMEO],
                            ],
                            [
                                "name" => "Team Building",
                                "description" => "with Ryan Bell",
                                "resources" => null,
                                "video" => ["link" => 563005622, "source" => VIMEO],
                            ],
                            [
                                "name" => "Rising Star Panel",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563005272, "source" => VIMEO],
                            ],
                        ],
                    ],
                ]
            ],
            [
                "name" => "90 Day Run",
                "description" => null,
                "thumbnail" => "https://docs-prod.nyc3.digitaloceanspaces.com/7350_-_first_90_days_templatesm_600x314.jpeg",
                "roles" => [ENAGIC_ROLE],
                "sections" => [
                    [
                        "name" => "90 Day Run", "description" => null,
                        //fake
                        "lessons" => [
                            [
                                "name" => "Promoting Events",
                                "description" => "with Colten Echave",
                                "resources" => null,
                                "video" => ["link" => "81lgyj312d", "source" => WISTIA],
                            ],
                            [
                                "name" => "public Speaking",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563004912, "source" => VIMEO],
                            ],
                            [
                                "name" => "Direct Sales",
                                "description" => "with Ben Landry",
                                "resources" => null,
                                "video" => ["link" => 563003350, "source" => VIMEO],

                            ],
                            [
                                "name" => "Professional Networking",
                                "description" => "with Jennifer McCormick",
                                "resources" => null,
                                "video" => ["link" => 563003789, "source" => VIMEO],
                            ],
                            [
                                "name" => "Closing The Sale",
                                "description" => "with Priscilla Echave",
                                "resources" => null,
                                "video" => ["link" => 563002931, "source" => VIMEO],
                            ],
                            [
                                "name" => "Team Building",
                                "description" => "with Ryan Bell",
                                "resources" => null,
                                "video" => ["link" => 563005622, "source" => VIMEO],
                            ],
                            [
                                "name" => "Rising Star Panel",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563005272, "source" => VIMEO],
                            ],
                        ],
                    ],
                ]
            ],
            [
                "name" => "Leadership",
                "description" => null,
                "thumbnail" => "https://docs-prod.nyc3.digitaloceanspaces.com/d19d5sz0wkl0lu.cloudfront.jpg",
                "roles" => [TRIFECTA_ROLE],
                "sections" => [
                    [
                        "name" => "Integrus Leadership Call", "description" => null,
                        //fake
                        "lessons" => [
                            [
                                "name" => "Promoting Events",
                                "description" => "with Colten Echave",
                                "resources" => null,
                                "video" => ["link" => "81lgyj312d", "source" => WISTIA],
                            ],
                            [
                                "name" => "public Speaking",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563004912, "source" => VIMEO],
                            ],
                            [
                                "name" => "Direct Sales",
                                "description" => "with Ben Landry",
                                "resources" => null,
                                "video" => ["link" => 563003350, "source" => VIMEO],

                            ],
                            [
                                "name" => "Professional Networking",
                                "description" => "with Jennifer McCormick",
                                "resources" => null,
                                "video" => ["link" => 563003789, "source" => VIMEO],
                            ],
                            [
                                "name" => "Closing The Sale",
                                "description" => "with Priscilla Echave",
                                "resources" => null,
                                "video" => ["link" => 563002931, "source" => VIMEO],
                            ],
                            [
                                "name" => "Team Building",
                                "description" => "with Ryan Bell",
                                "resources" => null,
                                "video" => ["link" => 563005622, "source" => VIMEO],
                            ],
                            [
                                "name" => "Rising Star Panel",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563005272, "source" => VIMEO],
                            ],
                        ],
                    ],
                ]
            ],
            [
                "name" => "Active Recruiter",
                "description" => null,
                "thumbnail" => null,
                "roles" => [ACTIVE_RECRUITER_ROLE],
                "sections" => [
                    [
                        "name" => "Leadership Call Replay", "description" => null,
                        //fake
                        "lessons" => [
                            [
                                "name" => "Promoting Events",
                                "description" => "with Colten Echave",
                                "resources" => null,
                                "video" => ["link" => "81lgyj312d", "source" => WISTIA],
                            ],
                            [
                                "name" => "public Speaking",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563004912, "source" => VIMEO],
                            ],
                            [
                                "name" => "Direct Sales",
                                "description" => "with Ben Landry",
                                "resources" => null,
                                "video" => ["link" => 563003350, "source" => VIMEO],

                            ],
                            [
                                "name" => "Professional Networking",
                                "description" => "with Jennifer McCormick",
                                "resources" => null,
                                "video" => ["link" => 563003789, "source" => VIMEO],
                            ],
                            [
                                "name" => "Closing The Sale",
                                "description" => "with Priscilla Echave",
                                "resources" => null,
                                "video" => ["link" => 563002931, "source" => VIMEO],
                            ],
                            [
                                "name" => "Team Building",
                                "description" => "with Ryan Bell",
                                "resources" => null,
                                "video" => ["link" => 563005622, "source" => VIMEO],
                            ],
                            [
                                "name" => "Rising Star Panel",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563005272, "source" => VIMEO],
                            ],
                        ],
                    ],
                ]
            ],
            [
                "name" => "Morning Huddle Replays",
                "description" => null,
                "thumbnail" => "https://docs-prod.nyc3.digitaloceanspaces.com/Daily%20Morning%20Power%20Huddles%20ipad.png",
                "roles" => [TRIFECTA_ROLE],
                "sections" => [
                    [
                        "name" => "February 2021", "description" => null,
                        //fake
                        "lessons" => [
                            [
                                "name" => "Promoting Events",
                                "description" => "with Colten Echave",
                                "resources" => null,
                                "video" => ["link" => "81lgyj312d", "source" => WISTIA],
                            ],
                            [
                                "name" => "public Speaking",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563004912, "source" => VIMEO],
                            ],
                            [
                                "name" => "Direct Sales",
                                "description" => "with Ben Landry",
                                "resources" => null,
                                "video" => ["link" => 563003350, "source" => VIMEO],

                            ],
                            [
                                "name" => "Professional Networking",
                                "description" => "with Jennifer McCormick",
                                "resources" => null,
                                "video" => ["link" => 563003789, "source" => VIMEO],
                            ],
                            [
                                "name" => "Closing The Sale",
                                "description" => "with Priscilla Echave",
                                "resources" => null,
                                "video" => ["link" => 563002931, "source" => VIMEO],
                            ],
                            [
                                "name" => "Team Building",
                                "description" => "with Ryan Bell",
                                "resources" => null,
                                "video" => ["link" => 563005622, "source" => VIMEO],
                            ],
                            [
                                "name" => "Rising Star Panel",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563005272, "source" => VIMEO],
                            ],
                        ],
                    ],
                    [
                        "name" => "March 2021", "description" => null,
                        //fake
                        "lessons" => [
                            [
                                "name" => "Promoting Events",
                                "description" => "with Colten Echave",
                                "resources" => null,
                                "video" => ["link" => "81lgyj312d", "source" => WISTIA],
                            ],
                            [
                                "name" => "public Speaking",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563004912, "source" => VIMEO],
                            ],
                            [
                                "name" => "Direct Sales",
                                "description" => "with Ben Landry",
                                "resources" => null,
                                "video" => ["link" => 563003350, "source" => VIMEO],

                            ],
                            [
                                "name" => "Professional Networking",
                                "description" => "with Jennifer McCormick",
                                "resources" => null,
                                "video" => ["link" => 563003789, "source" => VIMEO],
                            ],
                            [
                                "name" => "Closing The Sale",
                                "description" => "with Priscilla Echave",
                                "resources" => null,
                                "video" => ["link" => 563002931, "source" => VIMEO],
                            ],
                            [
                                "name" => "Team Building",
                                "description" => "with Ryan Bell",
                                "resources" => null,
                                "video" => ["link" => 563005622, "source" => VIMEO],
                            ],
                            [
                                "name" => "Rising Star Panel",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563005272, "source" => VIMEO],
                            ],
                        ],
                    ],
                    [
                        "name" => "April 2021", "description" => null,
                        //fake
                        "lessons" => [
                            [
                                "name" => "Promoting Events",
                                "description" => "with Colten Echave",
                                "resources" => null,
                                "video" => ["link" => "81lgyj312d", "source" => WISTIA],
                            ],
                            [
                                "name" => "public Speaking",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563004912, "source" => VIMEO],
                            ],
                            [
                                "name" => "Direct Sales",
                                "description" => "with Ben Landry",
                                "resources" => null,
                                "video" => ["link" => 563003350, "source" => VIMEO],

                            ],
                            [
                                "name" => "Professional Networking",
                                "description" => "with Jennifer McCormick",
                                "resources" => null,
                                "video" => ["link" => 563003789, "source" => VIMEO],
                            ],
                            [
                                "name" => "Closing The Sale",
                                "description" => "with Priscilla Echave",
                                "resources" => null,
                                "video" => ["link" => 563002931, "source" => VIMEO],
                            ],
                            [
                                "name" => "Team Building",
                                "description" => "with Ryan Bell",
                                "resources" => null,
                                "video" => ["link" => 563005622, "source" => VIMEO],
                            ],
                            [
                                "name" => "Rising Star Panel",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563005272, "source" => VIMEO],
                            ],
                        ],
                    ],
                    [
                        "name" => "May 2021", "description" => null,
                        //fake
                        "lessons" => [
                            [
                                "name" => "Promoting Events",
                                "description" => "with Colten Echave",
                                "resources" => null,
                                "video" => ["link" => "81lgyj312d", "source" => WISTIA],
                            ],
                            [
                                "name" => "public Speaking",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563004912, "source" => VIMEO],
                            ],
                            [
                                "name" => "Direct Sales",
                                "description" => "with Ben Landry",
                                "resources" => null,
                                "video" => ["link" => 563003350, "source" => VIMEO],

                            ],
                            [
                                "name" => "Professional Networking",
                                "description" => "with Jennifer McCormick",
                                "resources" => null,
                                "video" => ["link" => 563003789, "source" => VIMEO],
                            ],
                            [
                                "name" => "Closing The Sale",
                                "description" => "with Priscilla Echave",
                                "resources" => null,
                                "video" => ["link" => 563002931, "source" => VIMEO],
                            ],
                            [
                                "name" => "Team Building",
                                "description" => "with Ryan Bell",
                                "resources" => null,
                                "video" => ["link" => 563005622, "source" => VIMEO],
                            ],
                            [
                                "name" => "Rising Star Panel",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563005272, "source" => VIMEO],
                            ],
                        ],
                    ],
                    [
                        "name" => "June 2021", "description" => null,
                        //fake
                        "lessons" => [
                            [
                                "name" => "Promoting Events",
                                "description" => "with Colten Echave",
                                "resources" => null,
                                "video" => ["link" => "81lgyj312d", "source" => WISTIA],
                            ],
                            [
                                "name" => "public Speaking",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563004912, "source" => VIMEO],
                            ],
                            [
                                "name" => "Direct Sales",
                                "description" => "with Ben Landry",
                                "resources" => null,
                                "video" => ["link" => 563003350, "source" => VIMEO],

                            ],
                            [
                                "name" => "Professional Networking",
                                "description" => "with Jennifer McCormick",
                                "resources" => null,
                                "video" => ["link" => 563003789, "source" => VIMEO],
                            ],
                            [
                                "name" => "Closing The Sale",
                                "description" => "with Priscilla Echave",
                                "resources" => null,
                                "video" => ["link" => 563002931, "source" => VIMEO],
                            ],
                            [
                                "name" => "Team Building",
                                "description" => "with Ryan Bell",
                                "resources" => null,
                                "video" => ["link" => 563005622, "source" => VIMEO],
                            ],
                            [
                                "name" => "Rising Star Panel",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563005272, "source" => VIMEO],
                            ],
                        ],
                    ],
                ]
            ],
            [
                "name" => "Advisor 101",
                "description" => null,
                "thumbnail" => "https://docs-prod.nyc3.digitaloceanspaces.com/leadership.jpg",
                "roles" => [ADVISOR_ROLE],
                "sections" => [
                    [
                        "name" => "Watch This First", "description" => null,
                        //fake
                        "lessons" => [
                            [
                                "name" => "Promoting Events",
                                "description" => "with Colten Echave",
                                "resources" => null,
                                "video" => ["link" => "81lgyj312d", "source" => WISTIA],
                            ],
                            [
                                "name" => "public Speaking",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563004912, "source" => VIMEO],
                            ],
                            [
                                "name" => "Direct Sales",
                                "description" => "with Ben Landry",
                                "resources" => null,
                                "video" => ["link" => 563003350, "source" => VIMEO],

                            ],
                            [
                                "name" => "Professional Networking",
                                "description" => "with Jennifer McCormick",
                                "resources" => null,
                                "video" => ["link" => 563003789, "source" => VIMEO],
                            ],
                            [
                                "name" => "Closing The Sale",
                                "description" => "with Priscilla Echave",
                                "resources" => null,
                                "video" => ["link" => 563002931, "source" => VIMEO],
                            ],
                            [
                                "name" => "Team Building",
                                "description" => "with Ryan Bell",
                                "resources" => null,
                                "video" => ["link" => 563005622, "source" => VIMEO],
                            ],
                            [
                                "name" => "Rising Star Panel",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563005272, "source" => VIMEO],
                            ],
                        ],
                    ],
                    [
                        "name" => "Leadership Trainings", "description" => null,
                        //fake
                        "lessons" => [
                            [
                                "name" => "Promoting Events",
                                "description" => "with Colten Echave",
                                "resources" => null,
                                "video" => ["link" => "81lgyj312d", "source" => WISTIA],
                            ],
                            [
                                "name" => "public Speaking",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563004912, "source" => VIMEO],
                            ],
                            [
                                "name" => "Direct Sales",
                                "description" => "with Ben Landry",
                                "resources" => null,
                                "video" => ["link" => 563003350, "source" => VIMEO],

                            ],
                            [
                                "name" => "Professional Networking",
                                "description" => "with Jennifer McCormick",
                                "resources" => null,
                                "video" => ["link" => 563003789, "source" => VIMEO],
                            ],
                            [
                                "name" => "Closing The Sale",
                                "description" => "with Priscilla Echave",
                                "resources" => null,
                                "video" => ["link" => 563002931, "source" => VIMEO],
                            ],
                            [
                                "name" => "Team Building",
                                "description" => "with Ryan Bell",
                                "resources" => null,
                                "video" => ["link" => 563005622, "source" => VIMEO],
                            ],
                            [
                                "name" => "Rising Star Panel",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563005272, "source" => VIMEO],
                            ],
                        ],
                    ],
                    [
                        "name" => "Recordings", "description" => null,
                        //fake
                        "lessons" => [
                            [
                                "name" => "Promoting Events",
                                "description" => "with Colten Echave",
                                "resources" => null,
                                "video" => ["link" => "81lgyj312d", "source" => WISTIA],
                            ],
                            [
                                "name" => "public Speaking",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563004912, "source" => VIMEO],
                            ],
                            [
                                "name" => "Direct Sales",
                                "description" => "with Ben Landry",
                                "resources" => null,
                                "video" => ["link" => 563003350, "source" => VIMEO],

                            ],
                            [
                                "name" => "Professional Networking",
                                "description" => "with Jennifer McCormick",
                                "resources" => null,
                                "video" => ["link" => 563003789, "source" => VIMEO],
                            ],
                            [
                                "name" => "Closing The Sale",
                                "description" => "with Priscilla Echave",
                                "resources" => null,
                                "video" => ["link" => 563002931, "source" => VIMEO],
                            ],
                            [
                                "name" => "Team Building",
                                "description" => "with Ryan Bell",
                                "resources" => null,
                                "video" => ["link" => 563005622, "source" => VIMEO],
                            ],
                            [
                                "name" => "Rising Star Panel",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563005272, "source" => VIMEO],
                            ],
                        ],
                    ],
                ]
            ],
            [
                "name" => "Capital Secrets",
                "description" => null,
                "thumbnail" => "https://docs-prod.nyc3.digitaloceanspaces.com/Capital%20Secrets%20Training%20ipad.png",
                "roles" => [ALL_MEMBER_ROLE],
                "sections" => [
                    [
                        "name" => "Credit Scores", "description" => null,
                        //fake
                        "lessons" => [
                            [
                                "name" => "Promoting Events",
                                "description" => "with Colten Echave",
                                "resources" => null,
                                "video" => ["link" => "81lgyj312d", "source" => WISTIA],
                            ],
                            [
                                "name" => "public Speaking",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563004912, "source" => VIMEO],
                            ],
                            [
                                "name" => "Direct Sales",
                                "description" => "with Ben Landry",
                                "resources" => null,
                                "video" => ["link" => 563003350, "source" => VIMEO],

                            ],
                            [
                                "name" => "Professional Networking",
                                "description" => "with Jennifer McCormick",
                                "resources" => null,
                                "video" => ["link" => 563003789, "source" => VIMEO],
                            ],
                            [
                                "name" => "Closing The Sale",
                                "description" => "with Priscilla Echave",
                                "resources" => null,
                                "video" => ["link" => 563002931, "source" => VIMEO],
                            ],
                            [
                                "name" => "Team Building",
                                "description" => "with Ryan Bell",
                                "resources" => null,
                                "video" => ["link" => 563005622, "source" => VIMEO],
                            ],
                            [
                                "name" => "Rising Star Panel",
                                "description" => null,
                                "resources" => null,
                                "video" => ["link" => 563005272, "source" => VIMEO],
                            ],
                        ],
                    ],
                ]
            ],
        ];

        /* $sections = [
             ["name" => "2019 R2F Experience", "description" => null,],
             ["name" => "6A Rank", "description" => "Get Ready!!!",],
             ["name" => "Start Here !!!", "description" => null],
             ["name" => "Compensation Plan", "description" => null,],
             ["name" => "Product Overview", "description" => null],
             ["name" => "Listen To Their Stories!!!", "description" => null],
             ["name" => "Frequently Asked Questions", "description" => null],
             ["name" => "Discovery Process", "description" => null],
             ["name" => "Product Partnership 101", "description" => null],
             ["name" => "Find Your Dream Customers", "description" => null],
             ["name" => "Instant Influencer Equation", "description" => "Know How To Show Up Online"],
             ["name" => "Connection Scripts", "description" => "Introduction Messages"],
             ["name" => "Scaling to $10,000 Months", "description" => "Easiest Ways To Grow Your Business"],
             ["name" => "1st Password", "description" => "Reach out to your sponsor for password #1"],
             ["name" => "2nd Password", "description" => "Reach out to your sponsor for password #2"],
             ["name" => "Colten Echave", "description" => "You Suddenly Lose Everything... What Would You do From Day 1 To Day 30 To Save Yourself"],

         ];*/

        foreach ($courses as $course) {
            $roles = $course['roles'];
            $sections = $course['sections'];

            unset($course['roles'], $course['sections']);
            $rolesId = $this->roleModel::whereIn('name', $roles)->pluck('id')->toArray();

            if (!empty($course["thumbnail"])) {
                $media = $this->mediaModel::firstOrCreate(["path" => $course["thumbnail"]], ["source" => SPACES_STORAGE]);
                $course["thumbnail_id"] = $media->id;
            }

            unset($course["thumbnail"]);
            $createdCourse = Course::create($course);
            $createdCourse->allowedAudienceRoles()->attach($rolesId);

            foreach ($sections as $section) {
                $lessons = $section['lessons'];
                unset($section['lessons']);

                $section = $createdCourse->sections()->create($section);

                foreach ($lessons as $lesson) {
                    $video = $lesson['video'];
                    unset($lesson['video']);

                    $video = Video::create([...$video, 'slug' => rand(11111111, 99999999)]);
                    $section->lessons()->create([...$lesson, "video_id" => $video->id]);
                }
            }
        }
    }
}
