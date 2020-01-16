<?php

namespace App\Actions\Migration;

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
        echo "\nLoading file {$dumpFilePath}\n";
        $handle = fopen($dumpFilePath, "r");

        while (!feof($handle)) {
            $line = fgets($handle);
            if ($this->ignoreLine($line)) {
                continue;
            }

            $data = $this->decodeLine($line);
            try {
                if ($this->skipLoading($data['id'])) {
                    continue;
                }

                $this->loadData($data);
            } catch (\Exception $e) {
                echo "Error loading data {$e->getMessage()}, file {$file}, line {$line}\n";
                return false;
            }
        }

        fclose($handle);

        $this->cleanup();
        return true;
    }
}