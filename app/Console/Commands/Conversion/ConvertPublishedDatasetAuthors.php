<?php

namespace App\Console\Commands\Conversion;

use App\Models\Dataset;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

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

    public function handle()
    {
//        Dataset::with('project.team.members', 'project.team.admins')
//               ->whereNotNull('published_at')
//               ->get()
//               ->each(function (Dataset $dataset) {
//                   echo "\n\n";
//                   $this->info("Dataset: {$dataset->name} with id {$dataset->id}");
//                   $this->info("Authors: {$dataset->authors}");
//                   $dataset->project->team->members
//                       ->merge($dataset->project->team->admins)
//                       ->each(function (User $user) {
//                           $this->info("{$user->name} with id {$user->id}");
//                       });
//               });
        $this->getDatasetAuthors()->each(function ($ds) {
            $dataset = Dataset::find($ds['id']);
            if (is_null($dataset)) {
                return;
            }
            $this->info("Updating datasets {$dataset->name}/{$dataset->id}");
            $dataset->update(['ds_authors' => $ds['external']]);
//            $this->info("{$ds['id']}\n");
        });
    }

    private function getDatasetAuthors(): Collection
    {
        $datasetsToFix = [
            [
                "id"       => 1,
                "external" => [
                    $this->addUser("Yong-Jie Hu", "University of Michigan", "huyo@umich.edu"),
                    $this->addUser("Ge Zhao", "Pennsylvania State University; Portland State University"),
                    $this->addUser("Mingfei Zhang", "University of Michigan", "mingfei@umich.edu"),
                    $this->addUser("Bin Bin", "University of Michigan"),
                    $this->addUser("Tyler Del Rose", "University of Michigan"),
                    $this->addUser("Qian Zhao", "Sinoma Science & Technology Co."),
                    $this->addUser("Qun Zu", "Sinoma Science & Technology Co."),
                    $this->addUser("Yang Chen", "Sinoma Science & Technology Co."),
                    $this->addUser("Xuekun Sun", "Continental Technology LLC"),
                    $this->addUser("Maarten de Jong",
                        "University of California, Berkeley; Space Exploration Technologies (SpaceX)"),
                    $this->addUser("Liang Qi", "University of Michigan", "qiliang@umich.edu"),
                ],
            ],
            [
                "id"       => 2,
                "users"    => [34, 65, 173, 304, 118, 47, 274, 198, 113, 196],
                "external" => [
                    $this->addUser("Stephen DeWitt", "University of Michigan", "stvdwtt@umich.edu"),
                    $this->addUser("Ellen Solomon", "University of Michigan", "esitz@umich.edu"),
                    $this->addUser("Anirudh Raju Natarajan", "University of California, Santa Barbara",
                        "anirudh@engineering.ucsb.edu"),
                    $this->addUser("Vicente Araullo-Peters", "University of Michigan", "avicente@umich.edu"),
                    $this->addUser("Shiva Rudraraju", "University of Wisconsin", "rudraa@umich.edu"),
                    $this->addUser("Larry Aagesen", "Idaho National Laboratory", "laagesen@umich.edu"),
                    $this->addUser("Brian Puchala", "University of Michigan", "bpuchal@umich.edu"),
                    $this->addUser("Emmanuelle Marquis", "University of Michigan", "emarq@umich.edu"),
                    $this->addUser("Anton van der Ven", "University of California, Santa Barbara"),
                    $this->addUser("Katsuyo Thornton", "University of Michigan", "kthorn@umich.edu"),
                    $this->addUser("John Allison", "University of Michigan", "johnea@umich.edu"),
                ],
                "order"    => [
                    304, 118, 34, 47, 274, 198, 65, 113, "Anton van der Ven", 196, 173,
                ],
            ],
            [
                "id"       => 6,
                "users"    => [16, 173],
                "external" => [
                    $this->addUser("Aeriel Murphy-Leonard", "University of Michigan", "aerielm@umich.edu"),
                    $this->addUser("John Allison", "University of Michigan", "johnea@umich.edu"),
                    $this->addUser("Darren Pagan", "Cornell High Energy Synchrotron Source"),
                    $this->addUser("Armand Beaudoin", "Cornell High Energy Synchrotron Source"),
                    $this->addUser("Matthew Miller", "Cornell University"),
                ],
                "order"    => [
                    16, 173, "Darren Pagan", "Armand Beaudoin", "Matthew Miller",
                ],
            ],
            [
                "id"       => 8,
                "users"    => [298],
                "external" => [
                    $this->addUser("Naga Sri Harsha Gunda", "University of California Santa Barbara",
                        "sriharsha.naga@gmail.com"),
                ],
                "order"    => [298],
            ],
            [
                "id"       => 9,
                "users"    => [298],
                "external" => [
                    $this->addUser("Naga Sri Harsha Gunda", "University of California Santa Barbara",
                        "sriharsha.naga@gmail.com"),

                ],
                "order"    => [298],
            ],
            [
                "id"       => 10,
                "external" => [
                    $this->addUser("W. Beck Andrews", "University of Michigan", "wband@umich.edu"),
                    $this->addUser("Katsuyo Thornton", "University of Michigan", "kthorn@umich.edu"),
                    $this->addUser("Kate Elder", "Northwestern University", "kateelder2022@u.northwestern.edu"),
                    $this->addUser("Peter Voorhees", "Northwestern University", ""),
                ],
            ],
            [
                "id"       => 15,
                "users"    => [290, 174, 173],
                "external" => [
                    $this->addUser("Qianying Shi", "University of Michigan", "shiqiany@umich.edu"),
                    $this->addUser("Sinsar A. Hsie", "University of Michigan"),
                    $this->addUser("J. Wayne Jones", "University of Michigan", "jonesjwa@umich.edu"),
                    $this->addUser("John E. Allison", "University of Michigan", "johnea@umich.edu"),
                ],
                "order"    => [
                    290, "Sinsar A. Hsie", 174, 173,
                ],
            ],
            [
                "id"       => 16,
                "users"    => [108, 316, 173],
                "external" => [
                    $this->addUser("Erin Deda", "University of Michigan", "ededa@umich.edu"),
                    $this->addUser("Tracy Berman", "University of Michigan", "tradiasa@umich.edu"),
                    $this->addUser("John Allison", "University of Michigan", "johnea@umich.edu"),
                ],
                "order"    => [108, 316, 173],
            ],
            [
                "id"       => 18,
                "external" => [
                    $this->addUser("Mohammadreza Yaghoobi", "University of Michigan, Ann Arbor, MI",
                        "yaghoobi@umich.edu"),
                    $this->addUser("John E. Allison", "University of Michigan, Ann Arbor, MI", "johnea@umich.edu"),
                    $this->addUser("Veera Sundararaghavan", "University of Michigan, Ann Arbor, MI",
                        "veeras@umich.edu"),
                ],
            ],
            [
                "id"       => 26,
                "users"    => [327],
                "external" => [
                    $this->addUser("W. Beck Andrews", "University of Michigan", "wband@umich.edu"),
                    $this->addUser("Katsuyo Thornton", "Univsersity of Michigan", "kthorn@umich.edu"),
                ],
                "order"    => [327],
            ],
            [
                "id"       => 27,
                "external" => [
                    $this->addUser("John Allison", "University of Michigan", "johnea@umich.edu"),
                ],
            ],
            [
                "id"       => 29,
                "users"    => [65],
                "external" => [
                    $this->addUser("Brian Puchala", "University of Michigan", "bpuchala@umich.edu"),
                ],
                "order"    => [65],
            ],
            [
                "id"       => 35,
                "external" => [
                    $this->addUser("Glenn Tarcea", "University of Michigan", "gtarcea@umich.edu"),
                ],
            ],
            [
                "id"       => 36,
                "users"    => [196, 327],
                "external" => [
                    $this->addUser("W. Beck Andrews", "University of Michigan", "wband@umich.edu"),
                    $this->addUser("Katsuyo Thornton", "University of Michigan", "kthorn@umich.edu"),
                ],
                "order"    => [327, 196],
            ],
            [
                "id"       => 37,
                "users"    => [316, 108, 173],
                "external" => [
                    $this->addUser("Tracy Berman", "University of Michigan", "tradiasa@umich.edu"),
                    $this->addUser("Erin Deda", "University of Michigan", "ededa@umich.edu"),
                    $this->addUser("Mei Li", "Ford Motor Company"),
                    $this->addUser("John Allison", "University of Michigan", "johnea@umich.edu"),

                ],
                "order"    => [
                    316, 108, "Mei Li", 173,
                ],
            ],
            [
                "id"       => 39,
                "users"    => [184, 113],
                "external" => [
                    $this->addUser("Kathleen Chou", "University of Michigan", "kachou@umich.edu"),
                    $this->addUser("Emmanuelle Marquis", "University of Michigan", "emarq@umich.edu"),
                ],
                "order"    => [184, 113],
            ],
            [
                "id"       => 42,
                "users"    => [298],
                "external" => [
                    $this->addUser("Naga Sri Harsha Gunda", "University of California Santa Barbara",
                        "sriharsha.naga@gmail.com"),
                ],
                "order"    => [298],
            ],
            [
                "id"       => 45,
                "users"    => [327, 196],
                "external" => [
                    $this->addUser("W. Beck Andrews", "University of Michigan", "wband@umich.edu"),
                    $this->addUser("Katsuyo Thornton", "University of Michigan", "kthorn@umich.edu"),
                ],
                "order"    => [327, 196],
            ],
            [
                "id"       => 47,
                "users"    => [184, 253, 113],
                "external" => [
                    $this->addUser("Kathleen Chou", "University of Michigan", "kachou@umich.edu"),
                    $this->addUser("Peng-Wei Chu", "University of Michigan", "pengwchu@umich.edu"),
                    $this->addUser("Carlos Levi", "University of California, Santa Barbara"),
                    $this->addUser("Emmanuelle Marquis", "University of Michigan", "emarq@umich.edu"),

                ],
                "order"    => [
                    184, 253, "Carlos Levi", 113,
                ],
            ],
            [
                "id"       => 48,
                "external" => [
                    $this->addUser("Tracy Berman", "University of Michigan", "tradiasa@umich.edu"),
                ],
            ],
            [
                "id"       => 50,
                "users"    => [226, 173],
                "external" => [
                    $this->addUser("Jiashi Miao", "University of Michigan", "miaojias@umich.edu"),
                    $this->addUser("Mei Li", "Ford Motor Company"),
                    $this->addUser("John Allison", "University of Michigan", "johnea@umich.edu"),
                ],
                "order"    => [226, "Mei Li", 173],
            ],
            [
                "id"       => 52,
                "users"    => [150, 81, 228, 258],
                "external" => [
                    $this->addUser("Yong-Jie Hu",
                        "Department of Materials Science and Engineering, University of Michigan", "huyo@umich.edu"),
                    $this->addUser("Zhao Ge", "Department of Statistics, Pennsylvania State University"),
                    $this->addUser("Baiyu Zhang",
                        "Department of Materials Science and Engineering, Texas A&M University"),
                    $this->addUser("Chaoming Yang",
                        "Department of Materials Science and Engineering, University of Michigan", "chaomy@umich.edu"),
                    $this->addUser("Mingfei Zhang",
                        "Department of Materials Science and Engineering, University of Michigan", "mingfei@umich.edu"),
                    $this->addUser("Zi-Kui Liu",
                        "Department of Materials Science and Engineering, Pennsylvania State University"),
                    $this->addUser("Xiaofeng Qian",
                        "Department of Materials Science and Engineering, Texas A&M University"),
                    $this->addUser("Liang Qi",
                        "Department of Materials Science and Engineering, University of Michigan", "qiliang@umich.edu"),
                ],
                "order"    => [
                    150, "Zhao Ge", "Baiyu Zhang", 81, 228, "Zi-Kui Liu", "Xiaofeng Qian", 258,
                ],
            ],
            [
                "id"       => 58,
                "users"    => [19, 299, 355, 173, 322, 365],
                "external" => [
                    $this->addUser("Alan Githen", "University of Michigan - Ann Arbor", "agithens@umich.edu"),
                    $this->addUser("Sriram Ganesan", "University of Michigan - Ann Arbor", "sriramg@umich.edu"),
                    $this->addUser("Zhe Chen", "University of California - Santa Barbara", "zhec@umich.edu"),
                    $this->addUser("John Allison", "University of Michigan - Ann Arbor", "johnea@umich.edu"),
                    $this->addUser("Veera Sundararaghavan", "University of Michigan - Ann Arbor", "veeras@umich.edu"),
                    $this->addUser("Samantha Daly", "University of California - Santa Barbara", "samdaly@ucsb.edu"),
                ],
                "order"    => [19, 299, 355, 173, 322, 365],
            ],
            [
                "id"       => 59,
                "users"    => [343, 299, 735, 11, 274, 173, 322],
                "external" => [
                    $this->addUser("Mohammadreza Yaghoobi",
                        "Materials Science and Engineering, University of Michigan, Ann Arbor, MI 48109, USA",
                        "yaghoobi@umich.edu"),
                    $this->addUser("Sriram Ganesan",
                        "Aerospace Engineering, University of Michigan, Ann Arbor, MI 48109, USA", "sriramg@umich.edu"),
                    $this->addUser("Srihari Sundar",
                        "Aerospace Engineering, University of Michigan, Ann Arbor, MI 48109, USA",
                        "sriharis@umich.edu"),
                    $this->addUser("Aaditya Lakshmanan",
                        "Aerospace Engineering, University of Michigan, Ann Arbor, MI 48109, USA",
                        "aadityal@umich.edu"),
                    $this->addUser("Shiva Rudraraju",
                        "Mechanical Engineering, University of Michigan, Ann Arbor, MI 48109, USA. Present Address: Mechanical Engineering, University of Wisconsin-Madison, WI 53706, USA",
                        "rudraa@umich.edu"),
                    $this->addUser("John E. Allison",
                        "Materials Science and Engineering, University of Michigan, Ann Arbor, MI 48109, USA",
                        "johnea@umich.edu"),
                    $this->addUser("Veera Sundararaghavan",
                        "Aerospace Engineering, University of Michigan, Ann Arbor, MI 48109, USA", "veeras@umich.edu"),
                ],
                "order"    => [343, 299, 735, 11, 274, 173, 322],
            ],
            [
                "id"       => 63,
                "external" => [
                    $this->addUser("Vir Nirankari", "University of Michigan", "virn@umich.edu"),
                    $this->addUser("Mei Li", "Ford Motor Company", ""),
                    $this->addUser("John Allison", "University of Michigan", "johnea@umich.edu"),
                ],
            ],
            [
                "id"       => 64,
                "users"    => [239, 11, 322, 173],
                "external" => [
                    $this->addUser("Mohsen Taheri Andani", "University of Michigan", "mtaheri@umich.edu"),
                    $this->addUser("Aaditya Lakshmanan", "University of Michigan", "aadityal@umich.edu"),
                    $this->addUser("Veera Sundararaghavan", "University of Michigan", "veeras@umich.edu"),
                    $this->addUser("John Allison", "University of Michigan", "johnea@umich.edu"),
                    $this->addUser("Amit Misra", "University of Michigan", "amitmis@umich.edu"),
                ],
                "order"    => [239, 11, 322, 173, "Amit Misra"],
            ],
            [
                "id"       => 67,
                "external" => [
                    $this->addUser("Brian Puchala", "University of Michigan", "bpuchala@umich.edu"),
                ],
            ],
            [
                "id"       => 70,
                "users"    => [34, 118, 65, 113],
                "external" => [
                    $this->addUser("Anirudh Natarajan", "University of Michigan", "anirudh@engineering.ucsb.edu"),
                    $this->addUser("Ellen Solomon", "University of Michigan", "esitz@umich.edu"),
                    $this->addUser("Brian Puchala", "University of Michigan", "bpuchala@umich.edu"),
                    $this->addUser("Emmanuelle Marquis", "University of Michigan", "emarq@umich.edu"),
                    $this->addUser("Anton Van der Ven", "University of Michigan", ""),
                ],
                "order"    => [34, 118, 65, 113, "Anton Van der Ven"],
            ],
            [
                "id"       => 71,
                "external" => [
                    $this->addUser("Peng-Wei Chu", "University of Michigan", "pengwchu@umich.edu"),
                    $this->addUser("Etienne Le Mire", "University of Michigan", ""),
                    $this->addUser("Emmanuelle Marquis", "University of Michigan", "emarq@umich.edu"),
                ],
            ],
            [
                "id"       => 75,
                "users"    => [253, 113],
                "external" => [
                    $this->addUser("Peng-Wei Chu", "University of Michigan", "pengwchu@umich.edu"),
                    $this->addUser("Etienne Le Mire", "University of Michigan", ""),
                    $this->addUser("Emmanuelle Marquis", "University of Michigan", "emarq@umich.edu"),
                ],
                "order"    => [253, "Etienne Le Mire", 113],
            ],
            [
                "id"       => 77,
                "users"    => [298],
                "external" => [
                    $this->addUser("Naga Sri Harsha Gunda", "University of California Santa Barbara",
                        "sriharsha.naga@gmail.com"),
                ],
                "order"    => [298],
            ],
            [
                "id"       => 78,
                "external" => [
                    $this->addUser("Jiashi Miao", "Ohio State University", "miaojias@umich.edu"),
                    $this->addUser("Mei Li", "Ford Motor Company", ""),
                    $this->addUser("John Allison", "University of Michigan", "johnea@umich.edu"),
                ],
            ],
            [
                // Showed no authors
                "id"       => 79,
                "users"    => [29, 329, 173],
                "external" => [
                    $this->addUser("Anna Trump", "University of Michigan", "amcollet@umich.edu"),
                    $this->addUser("Wenaho Sun", "", ""),
                    $this->addUser("John Allison", "University of Michigan", "johnea@umich.edu"),
                ],
                "order"    => [29, 329, 173],
            ],
            [
                "id"       => 83,
                "users"    => [184, 253, 113],
                "external" => [
                    $this->addUser("Kathleen Chou", "University of Michigan", "kachou@umich.edu"),
                    $this->addUser("Peng-Wei Chu", "University of Michigan", "pengwchu@umich.edu"),
                    $this->addUser("Emmanuelle Marquis", "University of Michigan", "emarq@umich.edu"),
                ],
                "order"    => [184, 253, 113],
            ],
            [
                "id"       => 84,
                "users"    => [286],
                "external" => [
                    $this->addUser("Sha Liu", "", "sha.liu@imdea.org"),
                ],
                "order"    => [286],
            ],
            [
                "id"       => 87,
                "users"    => [304, 118, 34, 47, 274, 198, 65, 113, 196, 173],
                "external" => [
                    $this->addUser("Stephen DeWitt", "University of Michigan", "stvdwtt@umich.edu"),
                    $this->addUser("Ellen Solomon", "University of Michigan", "esitz@umich.edu"),
                    $this->addUser("Anirudh Natarajan", "University of California, Santa Barbara",
                        "anirudh@engineering.ucsb.edu"),
                    $this->addUser("Vicente Araullo-Peters", "University of Michigan", "avicente@umich.edu"),
                    $this->addUser("Shiva Rudraraju", "University of Michigan", "rudraa@umich.edu"),
                    $this->addUser("Larry Aagesen", "University of Michigan", "laagesen@umich.edu"),
                    $this->addUser("Brian Puchala", "University of Michigan", "bpuchala@umich.edu"),
                    $this->addUser("Emmanuelle Marquis", "University of Michigan", "emarq@umich.edu"),
                    $this->addUser("Anton van der Ven", "University of California, Santa Barbara"),
                    $this->addUser("Katsuyo Thornton", "University of Michigan", "kthorn@umich.edu"),
                    $this->addUser("John Allison", "University of Michigan", "johnea@umich.edu"),
                ],
                "order"    => [304, 118, 34, 47, 274, 198, 65, 113, "Anton van der Ven", 196, 173],
            ],
            [
                "id"       => 91,
                "users"    => [304],
                "external" => [
                    $this->addUser("Stephen DeWitt", "University of Michigan", "stvdwtt@umich.edu"),
                ],
                "order"    => [304],
            ],
            [
                "id"       => 93,
                "external" => [
                    $this->addUser("Anirudh Natarajan", "University of California, Santa Barbara",
                        "anirudh@engineering.ucsb.edu"),
                    $this->addUser("Anton van der Ven", "University of California, Santa Barbara"),
                ],
            ],
            [
                "id"       => 94,
                "users"    => [298],
                "external" => [
                    $this->addUser("Naga Sri Harsha Gunda", "University of California Santa Barbara",
                        "sriharsha.naga@gmail.com"),
                ],
                "order"    => [298],
            ],
            [
                "id"       => 95,
                "users"    => [327, 196],
                "external" => [
                    $this->addUser("W. Beck Andrews", "University of Michigan", "wband@umich.edu"),
                    $this->addUser("Katsuyo Thornton", "University of Michigan", "kthorn@umich.edu"),
                ],
                "order"    => [327, 196],
            ],
            [
                "id"       => 98,
                "external" => [
                    $this->addUser("Brian Puchala", "University of Michigan", "bpuchala@umich.edu"),
                ],
            ],
            [
                "id"       => 99,
                "users"    => [304, 274, 101, 327, 196],
                "external" => [
                    $this->addUser("Stephen DeWitt", "University of Michigan", "stvdwtt@umich.edu"),
                    $this->addUser("Shiva Rudraraju", "University of Michigan", "rudraa@umich.edu"),
                    $this->addUser("David Montiel", "University of Michigan", "dmontiel@umich.edu"),
                    $this->addUser("W. Beck Andrews", "University of Michigan", "wband@umich.edu"),
                    $this->addUser("Katsuyo Thornton", "University of Michigan", "kthorn@umich.edu"),
                ],
                "order"    => [304, 274, 101, 327, 196],
            ],
            [
                "id"       => 102,
                "external" => [
                    $this->addUser("W. Beck Andrews", "University of Michigan", "wband@umich.edu"),
                    $this->addUser("Katsuyo Thornton", "University of Michigan", "kthorn@umich.edu"),
                    $this->addUser("Kate Elder", "Northwestern University", "kateelder2022@u.northwestern.edu"),
                    $this->addUser("Peter Voorhees", "Northwestern University", ""),
                ],
            ],
            [
                "id"       => 106,
                "users"    => [327, 196, 187],
                "external" => [
                    $this->addUser("W. Beck Andrews", "University of Michigan", "wband@umich.edu"),
                    $this->addUser("Katsuyo Thornton", "University of Michigan", "kthorn@umich.edu"),
                    $this->addUser("Kate Elder", "Northwestern University", "kateelder2022@u.northwestern.edu"),
                    $this->addUser("Peter Voorhees", "Northwestern University", ""),
                ],
                "order"    => [327, 196, "Peter Voorhees", 187],
            ],
            [
                "id"       => 108,
                "external" => [
                    $this->addUser("Anirudh Natarajan", "University of California, Santa Barbara",
                        "anirudh@engineering.ucsb.edu"),
                    $this->addUser("Brian Puchala", "University of Michigan", "bpuchala@umich.edu"),
                    $this->addUser("Anton van der Ven", "University of California, Santa Barbara"),
                ],
            ],
            [
                "id"       => 109,
                "users"    => [159, 173, 174],
                "external" => [
                    $this->addUser("Jacob F. Adams",
                        "Department of Materials Science and Engineering, University of Michigan", "jfadams@umich.edu"),
                    $this->addUser("John E. Allison",
                        "Department of Materials Science and Engineering, University of Michigan", "johnea@umich.edu"),
                    $this->addUser("J. Wayne Jones",
                        "Department of Materials Science and Engineering, University of Michigan",
                        "jonesjwa@umich.edu"),
                ],
                "order"    => [159, 173, 174],
            ],
            [
                "id"       => 115,
                "users"    => [355, 365],
                "external" => [
                    $this->addUser("Zhe Chen", "University of California, Santa Barbara", "zhec@umich.edu"),
                    $this->addUser("Samantha Daly", "University of California, Santa Barbara", "samdaly@ucsb.edu"),
                ],
                "order"    => [355, 365],
            ],
            [
                "id"       => 116,
                "external" => [
                    $this->addUser("Zhe Chen", "University of California, Santa Barbara", "zhec@umich.edu"),
                ],
            ],
            [
                "id"       => 117,
                "external" => [
                    $this->addUser("Glenn Tarcea", "University of Michigan", "gtarcea@umich.edu"),
                ],
            ],
            [
                "id"       => 118,
                "external" => [
                    $this->addUser("Mohammadreza Yaghoobi", "University of Michigan, Ann Arbor, MI",
                        "yaghoobi@umich.edu"),
                    $this->addUser("John E. Allison", "University of Michigan, Ann Arbor, MI", "johnea@umich.edu"),
                    $this->addUser("Veera Sundararaghavan", "University of Michigan, Ann Arbor, MI",
                        "veeras@umich.edu"),
                ],
            ],
            [
                "id"       => 119,
                "users"    => [295, 196],
                "external" => [
                    $this->addUser("Susan P. Gentry",
                        "University of Michigan; University of California, Davis (present)", "spgentry@ucdavis.edu"),
                    $this->addUser("Katsuyo Thornton", "University of Michigan", "kthorn@umich.edu"),
                ],
                "order"    => [295, 196],
            ],
            [
                "id"       => 120,
                "external" => [
                    $this->addUser("Glenn Tarcea", "University of Michigan", "gtarcea@umich.edu"),
                ],
            ],
            [
                "id"       => 121,
                "users"    => [113],
                "external" => [
                    $this->addUser("Emmanuelle Marquis", "University of Michigan", "emarq@umich.edu"),

                ],
                "order"    => [113],
            ],
            [
                "id"       => 123,
                "users"    => [239, 11, 322, 173, 30],
                "external" => [
                    $this->addUser("Mohsen Taheri Andani", "University of Michigan", "mtaheri@umich.edu"),
                    $this->addUser("Aaditya Lakshmanan", "University of Michigan", "aadityal@umich.edu"),
                    $this->addUser("Veera Sundararaghavan", "University of Michigan", "veera@umich.edu"),
                    $this->addUser("John Allison", "University of Michigan", "johnea@umich.edu"),
                    $this->addUser("Amit Misra", "University of Michigan", "amitmis@umich.edu"),
                ],
                "order"    => [239, 11, 322, 173, 30],
            ],
            [
                "id"       => 124,
                "users"    => [82],
                "external" => [
                    $this->addUser("Charles R. Fisher",
                        "Naval Surface Warfare Center, Carderock Division [NSWCCD]", "charles.r.fisher@gmail.com"),
                    $this->addUser("Daniel H. Bechetti",
                        "Naval Surface Warfare Center, Carderock Division [NSWCCD]"),
                    $this->addUser("Jennifer K. Semple",
                        "Naval Surface Warfare Center, Carderock Division [NSWCCD]"),
                    $this->addUser("Wei Zhang", "Ohio State University [OSU]"),
                ],
                "order"    => [82, "Daniel H. Bechetti", "Jennifer K. Semple", "Wei Zhang"],
            ],
            [
                "id"       => 125,
                "users"    => [290, 316, 173],
                "external" => [
                    $this->addUser("Qianying Shi", "University of Michigan", "shiqiany@umich.edu"),
                    $this->addUser("Yang Huo", "Ford Motor Company"),
                    $this->addUser("Tracy Berman", "University of Michigan", "triadasa@umich.edu"),
                    $this->addUser("Bita Ghaffari", "Ford Motor Company"),
                    $this->addUser("Mei Li", "Ford Motor Company"),
                    $this->addUser("John Allison", "University of Michigan", "johnea@umich.edu"),
                ],
                "order"    => [290, "Yang Huo", 316, "Bita Ghaffari", "Mei Li", 173],
            ],
            [
                "id"       => 126,
                "users"    => [299, 343, 19, 355, 365, 173, 322],
                "external" => [
                    $this->addUser("Sriram Ganesan", "University of Michigan - Ann Arbor", "sriramg@umich.edu"),
                    $this->addUser("Mohammadreza Yaghoobi", "University of Michigan - Ann Arbor", "yagnoobi@umich.edu"),
                    $this->addUser("Alan Githens", "University of Michigan - Ann Arbor", "agithens@umich.edu"),
                    $this->addUser("Zhe Chen", "University of California - Santa Barbara", "zhec@umich.edu"),
                    $this->addUser("Samantha Daly", "University of California - Santa Barbara", "samdaly@ucsb.edu"),
                    $this->addUser("John Allison", "University of Michigan - Ann Arbor", "johnea@umich.edu"),
                    $this->addUser("Veera Sundararaghavan", "University of Michigan - Ann Arbor", "veeras@umich.edu"),
                ],
                "order"    => [299, 343, 19, 355, 365, 173, 322],
            ],
            [
                "id"       => 127,
                "users"    => [345],
                "external" => [
                    $this->addUser("Zhenjie Yao", "University of Michigan", "ycollin@umich.edu"),
                ],
                "order"    => [345],
            ],
            [
                "id"       => 128,
                "users"    => [345],
                "external" => [
                    $this->addUser("Zhenjie Yao", "University of Michigan", "ycollin@umich.edu"),
                ],
                "order"    => [345],
            ],
            [
                "id"       => 129,
                "external" => [
                    $this->addUser("Brian Puchala", "University of Michigan", "bpuchala@umich.edu"),
                ],
            ],
            [
                "id"       => 130,
                "users"    => [355, 755],
                "external" => [
                    $this->addUser("Zhe Chen", "University of California - Santa Barbara", "zhec@umich.edu"),
                    $this->addUser("Ryan Sperry", "", ""),
                ],
                "order"    => [355, 755],
            ],
            [
                "id"       => 131,
                "external" => [
                    $this->addUser("Ryan Sperry", "", ""),
                ],
            ],
            [
                "id"       => 134,
                "external" => [
                    $this->addUser("Zhe Chen", "University of California - Santa Barbara", "zhec@umich.edu"),
                ],
            ],

            [
                "id"       => 150,
                "external" => [
                    $this->addUser("W. Beck Andrews", "University of Michigan", "wband@umich.edu"),
                    $this->addUser("Katsuyo Thornton", "University of Michigan", "kthorn@umich.edu"),
                ],
            ],
            [
                "id"       => 151,
                "external" => [
                    $this->addUser("John Allison", "University of Michigan", "johnea@umich.edu"),
                ],
            ],
            [
                "id"       => 155,
                "external" => [
                    $this->addUser("Tracy Berman", "University of Michigan", "tradiasa@umich.edu"),
                    $this->addUser("John Allison", "University of Michigan", "johnea@umich.edu"),
                ],
            ],
            [
                "id"       => 156,
                "external" => [
                    $this->addUser("Mohammadreza yaghoobi", "University of Michigan", "yaghoobi@umich.edu"),
                ],
            ],
            [
                "id"       => 158,
                "external" => [
                    $this->addUser("Tracy Berman", "University of Michigan", "tradiasa@umich.edu"),
                    $this->addUser("John Allison", "University of Michigan", "johnea@umich.edu"),
                ],
            ],
            [
                "id"       => 161,
                "external" => [
                    $this->addUser("Mohammadreza Yaghoobi", "University of Michigan", "yaghoobi@umich.edu"),
                    $this->addUser("Krzysztof S. Stopka", "", "stopka.kris@gmail.com"),
                    $this->addUser("Aaditya Lakshmanan", "University of Michigan", "aadityal@umich.edu"),
                    $this->addUser("Veera Sundararaghavan", "University of Michigan", "veeras@umich.edu"),
                    $this->addUser("John E. Allison", "University of Michigan", "johnea@umich.edu"),
                    $this->addUser("David L. McDowell", "", ""),
                ],
            ],
            [
                "id"       => 162,
                "external" => [
                    $this->addUser("Mohammadreza Yaghoobi", "University of Michigan", "yaghoobi@umich.edu"),
                    $this->addUser("Zhe Chen", "UCSB", "zhec@umich.edu"),
                    $this->addUser("Veera Sundararaghavan", "University of Michigan", "veeras@umich.edu"),
                    $this->addUser("John E. Allison", "University of Michigan", "johnea@umich.edu"),
                    $this->addUser("Samantha Daly", "UCSB", "samdaly@ucsb.edu"),
                ],
            ],
            [
                "id"       => 163,
                "external" => [
                    $this->addUser("Tracy Berman", "University of Michigan", "tradiasa@umich.edu"),
                    $this->addUser("John Allison", "University of Michigan", "johnea@umich.edu"),
                ],
            ],
            [
                "id"       => 164,
                "external" => [
                    $this->addUser("Yiyang Li", "Sandia National Labs", "yiyangli@umich.edu"),
                    $this->addUser("T Patrick Xiao", "Sandia National Labs", ""),
                    $this->addUser("Christopher H Bennett", "Sandia National Labs", ""),
                    $this->addUser("Erik Isele", "Sandia National Labs", ""),
                    $this->addUser("Hanbo Tao", "Sandia National Labs", ""),
                    $this->addUser("Matthew J Marinella", "Sandia National Labs", ""),
                    $this->addUser("Elliot J Fuller", "Sandia National Labs", ""),
                    $this->addUser("A Alec Talin", "Sandia National Labs", ""),
                    $this->addUser("Armantas Melianas", "Stanford University", ""),
                    $this->addUser("Alberto Salleo", "Stanford University", ""),
                ],
            ],
            [
                "id"       => 165,
                "external" => [
                    $this->addUser("Haixing Fang", "Technical University of Denmark", "hfang@mek.dtu.dk"),
                    $this->addUser("Dorte Juul Jensen", "Technical University of Denmark", "doje@dtu.dk"),
                    $this->addUser("Yubin Zhang", "Technical University of Denmark", "yubz@mek.dtu.dk"),
                ],
            ],
            [
                "id"       => 166,
                "external" => [
                    $this->addUser("Haixing Fang", "Technical University of Denmark", "hfang@mek.dtu.dk"),
                    $this->addUser("Emil Hovad", "Technical University of Denmark", ""),
                    $this->addUser("Yubin Zhang", "Technical University of Denmark", "yubz@mek.dtu.dk"),
                    $this->addUser("Dorte Juul Jensen", "Technical University of Denmark", "doje@dtu.dk"),
                ],
            ],
            [
                "id"       => 167,
                "external" => [
                    $this->addUser("Haixing Fang", "Technical University of Denmark", "hfang@mek.dtu.dk"),
                    $this->addUser("Emil Hovad", "Technical University of Denmark", ""),
                    $this->addUser("Yubin Zhang", "Technical University of Denmark", "yubz@mek.dtu.dk"),
                    $this->addUser("Dorte Juul Jensen", "Technical University of Denmark", "doje@dtu.dk"),
                ],
            ],
            [
                "id"       => 168,
                "external" => [
                    $this->addUser("John Allison", "University of Michigan", "johnea@umich.edu"),
                    $this->addUser("Tracy Berman", "University of Michigan", "tradiasa@umich.edu"),
                ],
            ],
            [
                "id"       => 170,
                "external" => [
                    $this->addUser("Guanglong Huang", "", "umjihgl@umich.edu"),
                    $this->addUser("Mojue Zhang", "", ""),
                    $this->addUser("David Montiel", "", "dmontiel@umich.edu",),
                    $this->addUser("Praveen Soundararajan", "", ""),
                    $this->addUser("Yusu Wang", "", ""),
                    $this->addUser("Jonathan Denney", "", ""),
                    $this->addUser("Adam Corrao", "", ""),
                    $this->addUser("Peter Khalifah", "", ""),
                    $this->addUser("Katsuyo Thornton", "", "kthorn@umich.edu"),
                ],
            ],
            [
                "id"       => 171,
                "external" => [
                    $this->addUser("Yong-Jie Hu", "Drexel University", "huyo@umich.edu"),
                ],
            ],
            [
                "id"       => 172,
                "external" => [
                    $this->addUser("Yong-Jie Hu", "Drexel University", "huyo@umich.edu"),

                ],
            ],
        ];

        return collect($datasetsToFix);
    }

    private function addUser($name, $affiliations, $email = "")
    {
        return [
            "name"         => $name,
            "affiliations" => $affiliations,
            "email"        => $email,
        ];
    }
}
