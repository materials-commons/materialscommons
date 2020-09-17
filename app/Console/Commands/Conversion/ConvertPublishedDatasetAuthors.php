<?php

namespace App\Console\Commands\Conversion;

use App\Models\Dataset;
use App\Models\User;
use Illuminate\Console\Command;

class ConvertPublishedDatasetAuthors extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc-convert:convert-published-dataset-authors';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Dataset::with('project.team.members', 'project.team.admins')
               ->whereNotNull('published_at')
               ->get()
               ->each(function (Dataset $dataset) {
                   echo "\n\n";
                   $this->info("Dataset: {$dataset->name} with id {$dataset->id}");
                   $this->info("Authors: {$dataset->authors}");
                   $dataset->project->team->members
                       ->merge($dataset->project->team->admins)
                       ->each(function (User $user) {
                           $this->info("{$user->name} with id {$user->id}");
                       });
               });
    }

    private function fixAuthors()
    {
        $datasetsToFix = [
            [
                "id"       => 1,
                "users"    => [150, 228, 258],
                "external" => [
                    $this->externalUser("Ge Zhao", "Pennsylvania State University; Portland State University"),
                    $this->externalUser("Bin Bin", "University of Michigan"),
                    $this->externalUser("Tyler Del Rose", "University of Michigan"),
                    $this->externalUser("Qian Zhao", "Sinoma Science & Technology Co."),
                    $this->externalUser("Qun Zu", "Sinoma Science & Technology Co."),
                    $this->externalUser("Yang Chen", "Sinoma Science & Technology Co."),
                    $this->externalUser("Xuekun Sun", "Continental Technology LLC"),
                    $this->externalUser("Maarten de Jong",
                        "University of California, Berkeley; Space Exploration Technologies (SpaceX)"),
                    $this->externalUser("", ""),
                    $this->externalUser("", ""),
                ],
                [
                    "id"       => 2,
                    "users"    => [34, 65, 173, 304, 118, 47, 274, 198, 113, 196],
                    "external" => [
                        $this->externalUser("Anton van der Ven", "University of California, Santa Barbara"),
                    ],
                ],
                [
                    "id"       => 6,
                    "users"    => [16, 173],
                    "external" => [
                        $this->externalUser("Darren Pagan", "Cornell High Energy Synchrotron Source"),
                        $this->externalUser("Armand Beaudoin", "Cornell High Energy Synchrotron Source"),
                        $this->externalUser("Matthew Miller", "Cornell University"),
                    ],
                ],
                [
                    "id"       => 8,
                    "users"    => [298],
                    "external" => [
                    ],
                ],
                [
                    "id"       => 9,
                    "users"    => [298],
                    "external" => [
                    ],
                ],
                [
                    "id"       => 15,
                    "users"    => [290, 174, 173],
                    "external" => [
                        $this->externalUser("Sinsar A. Hsie", "University of Michigan"),
                    ],
                ],
                [
                    "id"       => 16,
                    "users"    => [108, 316, 173],
                    "external" => [
                    ],
                ],
                [
                    "id"       => 26,
                    "users"    => [327],
                    "external" => [
                    ],
                ],
                [
                    "id"       => 29,
                    "users"    => [65],
                    "external" => [
                    ],
                ],
                [
                    "id"       => 75,
                    "users"    => [65],
                    "external" => [
                    ],
                ],
                [
                    "id"       => 36,
                    "users"    => [196, 327],
                    "external" => [
                    ],
                ],
                [
                    "id"       => 37,
                    "users"    => [316, 108, 173],
                    "external" => [
                        $this->externalUser("Mei Li", "Ford Motor Company"),
                    ],
                ],
                [
                    "id"       => 39,
                    "users"    => [184, 113],
                    "external" => [
                    ],
                ],
                [
                    "id"       => 42,
                    "users"    => [298],
                    "external" => [
                    ],
                ],
                [
                    "id"       => 45,
                    "users"    => [327, 196],
                    "external" => [
                    ],
                ],
                [
                    "id"       => 47,
                    "users"    => [184, 253, 113],
                    "external" => [
                        $this->externalUser("Carlos Levi", "University of California, Santa Barbara"),
                    ],
                ],
                [
                    "id"       => 50,
                    "users"    => [],
                    "external" => [
                        $this->externalUser("Mei Li", "Ford Motor Company"),
                    ],
                ],
                [
                    "id"       => 52,
                    "users"    => [150, 81, 228, 258],
                    "external" => [
                        $this->externalUser("Zhao Ge", "Department of Statistics, Pennsylvania State University"),
                        $this->externalUser("Baiyu Zhang",
                            "Department of Materials Science and Engineering, Texas A&M University"),
                        $this->externalUser("Zi-Kui Liu",
                            "Department of Materials Science and Engineering, Pennsylvania State University"),
                        $this->externalUser("Xiaofeng Qian",
                            "Department of Materials Science and Engineering, Texas A&M University"),
                    ],
                ],
                [
                    "id"       => 58,
                    "users"    => [19, 299, 355, 173, 322, 365],
                    "external" => [
                    ],
                ],
                [
                    "id"       => 59,
                    "users"    => [343, 299, 735, 11, 274, 173, 322],
                    "external" => [
                    ],
                ],
                [
                    "id"       => 64,
                    "users"    => [239, 11, 322, 173],
                    "external" => [
                    ],
                ],
                [
                    "id"       => 70,
                    "users"    => [34, 118, 65, 113],
                    "external" => [
                        $this->externalUser("Anton Van der Ven", "University of Michigan"),
                    ],
                ],
                [
                    "id"       => 75,
                    "users"    => [253, 113],
                    "external" => [
                        $this->externalUser("Etienne Le Mire", "University of Michigan"),
                    ],
                ],
                [
                    "id"       => 77,
                    "users"    => [298],
                    "external" => [
                    ],
                ],
                [
                    "id"       => 79,
                    "users"    => [29, 329, 173],
                    "external" => [
                    ],
                ],
                [
                    "id"       => 83,
                    "users"    => [184, 253, 113],
                    "external" => [
                    ],
                ],
                [
                    "id"       => 84,
                    "users"    => [286],
                    "external" => [
                    ],
                ],
                [
                    "id"       => 87,
                    "users"    => [304, 118, 34, 47, 274, 198, 65, 113, 196, 173],
                    "external" => [
                        $this->externalUser("Anton van der Ven", "University of California, Santa Barbara"),
                    ],
                ],
                [
                    "id"       => 91,
                    "users"    => [304],
                    "external" => [
                    ],
                ],
                [
                    "id"       => 94,
                    "users"    => [298],
                    "external" => [
                    ],
                ],
                [
                    "id"       => 95,
                    "users"    => [327, 196],
                    "external" => [
                    ],
                ],
                [
                    "id"       => 99,
                    "users"    => [304, 274, 101, 327, 196],
                    "external" => [
                    ],
                ],
                [
                    "id"       => 106,
                    "users"    => [327, 196, 187],
                    "external" => [
                        $this->externalUser("Peter Voorhees", "Northwestern University"),
                    ],
                ],
                [
                    "id"       => 109,
                    "users"    => [159, 173, 174],
                    "external" => [
                    ],
                ],
                [
                    "id"       => 115,
                    "users"    => [355, 365],
                    "external" => [
                    ],
                ],
                [
                    "id"       => 118,
                    "users"    => [343],
                    "external" => [
                    ],
                ],
                [
                    "id"       => 119,
                    "users"    => [295, 196],
                    "external" => [
                    ],
                ],
                [
                    "id"       => 121,
                    "users"    => [113],
                    "external" => [
                    ],
                ],
                [
                    "id"       => 123,
                    "users"    => [239, 11, 322, 173, 30],
                    "external" => [
                    ],
                ],
                [
                    "id"       => 124,
                    "users"    => [82],
                    "external" => [
                        $this->externalUser("Daniel H. Bechetti",
                            "Naval Surface Warfare Center, Carderock Division [NSWCCD]"),
                        $this->externalUser("Jennifer K. Semple",
                            "Naval Surface Warfare Center, Carderock Division [NSWCCD]"),
                        $this->externalUser("Wei Zhang", "Ohio State University [OSU]"),
                    ],
                ],
                [
                    "id"       => 125,
                    "users"    => [290, 316, 173],
                    "external" => [
                        $this->externalUser("Yang Huo", "Ford Motor Company"),
                        $this->externalUser("Bita Ghaffari", "Ford Motor Company"),
                        $this->externalUser("Mei Li", "Ford Motor Company"),
                    ],
                ],
                [
                    "id"       => 126,
                    "users"    => [299, 343, 19, 355, 365, 173, 322],
                    "external" => [
                    ],
                ],
                [
                    "id"       => 127,
                    "users"    => [345],
                    "external" => [
                    ],
                ],
                [
                    "id"       => 130,
                    "users"    => [355, 755],
                    "external" => [
                    ],
                ],
            ],
        ];
    }

    private function externalUser($name, $affiliations)
    {
        // TODO: Look up existing external user by name
        return [
            "name"         => $name,
            "affiliations" => $affiliations,
        ];
    }
}
