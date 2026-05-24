<?php

namespace App\Console\Commands;

use App\Models\Dataset;
use App\Models\Download;
use App\Models\View;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use function config;

class RemoveBotsFromDownloadViewCountsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc:remove-bots-from-download-view-counts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleans up the download and view counts';

    private $badAddresses;

    private $ids;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->badAddresses = array();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->ids = $this->getNonOpenVisusDatasetIds();
        echo "Length of ids is {$this->ids->count()}\n";
        $botsList = [];
        $handle = fopen("/tmp/COUNTER_Robots_list.txt", "r");
        while (($line = fgets($handle)) !== false) {
            array_push($botsList, Str::of($line)->rtrim()->__toString());
        }
        fclose($handle);

        $this->info("Download Counts:");
        $this->determineCounts(Download::cursor(), Download::cursor(), "downloadable_id", $botsList);
        echo("\n\n");
        $this->info("View Counts:");
        $this->determineCounts(View::cursor(), View::cursor(), "viewable_id", $botsList);
        return 0;
    }

    function determineCounts($cursor, $cursor2, $idName, $botsList)
    {
        $countsById = array();
        foreach ($cursor as $item) {
            if (!$this->ids->contains($item[$idName])) {
                continue;
            }
            $countsById[$item[$idName]] = 0;
        }

        foreach ($cursor2 as $item) {
            if (!$this->ids->contains($item[$idName])) {
                continue;
            }
            if (Str::contains($item->who, ".") && !Str::contains($item->who, "@")) {
                $host = $this->tryGetHost($item->who);
                if ($host !== $item->who) {
                    if ($this->isBot($host, $botsList)) {
                        $item->delete();
                    } else {
                        $count = $countsById[$item[$idName]];
                        $count++;
                        $countsById[$item[$idName]] = $count;
                    }
                } else {
                    $count = $countsById[$item[$idName]];
                    $count++;
                    $countsById[$item[$idName]] = $count;
                }
            } else {
                if (!Str::contains($item->who, "unknown")) {
                    $count = $countsById[$item[$idName]];
                    $count++;
                    $countsById[$item[$idName]] = $count;
                }
            }
        }

        $table = array();
        $totalCount = 0;
        foreach ($countsById as $id => $count) {
            $totalCount = $totalCount + $count;
            $dataset = Dataset::find($id);
            if (!is_null($dataset)) {
                array_push($table, [Str::limit($dataset->name, 50, "..."), $count]);
            }
        }

        $this->table(["Dataset", "Count"], $table);

        echo "  Total Count: {$totalCount}\n";
    }

    function tryGetHost($ip)
    {
        if (array_key_exists($ip, $this->badAddresses)) {
            return $ip;
        }

        $string = '';
        exec("dig +short -x $ip 2>&1", $output, $retval);
        if ($retval != 0) {
            // there was an error performing the command
        } else {
            $x = 0;
            while ($x < (sizeof($output))) {
                $string .= $output[$x];
                $x++;
            }
        }

        if (empty($string)) {
            $this->badAddresses[$ip] = true;
            $string = $ip;
        } else {
            //remove the trailing dot
            $string = substr($string, 0, -1);
        }

        return $string;
    }

    private function isBot(string $host, array $botsList)
    {
        foreach ($botsList as $botMatch) {
            if (Str::of($host)->match("/{$botMatch}/")->__toString() != "") {
                return true;
            }
        }
        return false;
    }

    private function getNonOpenVisusDatasetIds(): Collection
    {
        return Dataset::query()
                      ->whereDoesntHave('tags', function ($q) {
                          $q->where('tags.id', config('visus.import_tag_id'));
                      })
                      ->whereNotNull('published_at')
                      ->select('id')
                      ->get();
    }
}
