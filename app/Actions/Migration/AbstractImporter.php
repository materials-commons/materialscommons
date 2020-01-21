<?php

namespace App\Actions\Migration;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

abstract class AbstractImporter
{
    use LineHandler;

    abstract protected function setup();

    abstract protected function cleanup();

    abstract protected function loadData($data);

    protected $pathToDumpfiles;
    protected $existing;
    protected $ignoreExisting;
    protected $table;

    public function __construct($pathToDumpfiles, $table, $ignoreExisting = false)
    {
        $this->pathToDumpfiles = $pathToDumpfiles;
        $this->table = $table;
        $this->ignoreExisting = $ignoreExisting;
        $this->existing = [];
    }

    protected function skipLoading($uuid)
    {
        if ($this->ignoreExisting) {
            return isset($this->existing[$uuid]);
        }

        return false;
    }

    private function loadExisting()
    {
        if (!$this->ignoreExisting) {
            return;
        }

        DB::table($this->table)->orderBy('id')->select('uuid')->chunk(1000, function ($items) {
            foreach ($items as $item) {
                $this->existing[$item->uuid] = true;
            }
        });
    }

    public function loadDumpfile($file)
    {
        $this->loadExisting();

        $this->setup();

        $dumpFilePath = "{$this->pathToDumpfiles}/${file}";
        $startedAt = Carbon::now()->setTimezone('America/Detroit')->toTimeString();
        echo "\nLoading file {$dumpFilePath} started at {$startedAt}\n";
        $handle = fopen($dumpFilePath, "r");

        $count = 0;
        $loadedCount = 0;
        while (!feof($handle)) {
            $line = fgets($handle);
            if ($this->ignoreLine($line)) {
                continue;
            }

            $data = $this->decodeLine($line);
            if ($this->skipLoading($data['id'])) {
                continue;
            }

            if ($this->loadData($data) != null) {
                $loadedCount++;
            }

            $count++;

            if ($count % 1000 == 0) {
                $now = Carbon::now()->setTimezone('America/Detroit')->toTimeString();
                echo "\n   Processed ${count}/Loaded {$loadedCount} entries at {$now}...\n";
            }
        }

        fclose($handle);

        $this->existing = [];
        $this->cleanup();
        $finishedAt = Carbon::now()->setTimezone('America/Detroit')->toTimeString();
        echo "\nFinished processing {$count} entries, loaded ${loadedCount} from file {$dumpFilePath} at {$finishedAt}\n";
        return true;
    }
}