<?php

namespace App\Actions\Migration;

abstract class AbstractImporter
{
    use LineHandler;

    abstract protected function setup();

    abstract protected function cleanup();

    abstract protected function loadData($data);

    protected $pathToDumpfiles;

    public function __construct($pathToDumpfiles)
    {
        $this->pathToDumpfiles = $pathToDumpfiles;
    }

    public function loadDumpfile($file)
    {
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