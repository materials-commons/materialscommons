<?php

namespace App\Actions\Migration;

trait ItemLoader
{
    use LineHandler;

    public function loadItemMapping($file, $key, $valueKey)
    {
        $knownItems = [];
        $project2sampleDumpfile = "{$this->pathToDumpfiles}/${file}";
        $handle = fopen($project2sampleDumpfile, "r");
        while (!feof($handle)) {
            $line = fgets($handle);
            if ($this->ignoreLine($line)) {
                continue;
            }
            $data = $this->decodeLine($line);
            $knownItems[$data[$key]] = $data[$valueKey];
        }

        fclose($handle);

        return $knownItems;
    }

    public function loadItemToObjectMapping($file, $key)
    {
        $knownItems = [];
        $project2sampleDumpfile = "{$this->pathToDumpfiles}/${file}";
        $handle = fopen($project2sampleDumpfile, "r");
        while (!feof($handle)) {
            $line = fgets($handle);
            if ($this->ignoreLine($line)) {
                continue;
            }
            $data = $this->decodeLine($line);
            $knownItems[$data[$key]] = $data;
        }

        fclose($handle);

        return $knownItems;
    }
}

