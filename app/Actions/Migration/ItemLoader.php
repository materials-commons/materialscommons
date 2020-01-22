<?php

namespace App\Actions\Migration;

trait ItemLoader
{
    use LineHandler;

    public function loadItemMapping($file, $key, $valueKey)
    {
        return $this->loadItems($file, $key, function (&$knownItems, $data, $key) use ($valueKey) {
            $knownItems[$data[$key]] = $data[$valueKey];
            return $knownItems;
        });
    }

    public function loadItemToObjectMapping($file, $key)
    {
        return $this->loadItems($file, $key, function (&$knownItems, $data, $key) {
            $knownItems[$data[$key]] = $data;
            return $knownItems;
        });
    }

    public function loadItemMappingMultiple($file, $key, $valueKey)
    {
        return $this->loadItems($file, $key, function (&$knownItems, $data, $key) use ($valueKey) {
            if (!isset($knownItems[$data[$key]])) {
                $knownItems[$data[$key]] = [];
            }
            array_push($knownItems[$data[$key]], $data[$valueKey]);
            return $knownItems;
        });
    }

    public function loadItemToObjectMappingMultiple($file, $key)
    {
        return $this->loadItems($file, $key, function (&$knownItems, $data, $key) {
            if (!isset($knownItems[$data[$key]])) {
                $knownItems[$data[$key]] = [];
            }
            array_push($knownItems[$data[$key]], $data);
            return $knownItems;
        });
    }

    public function loadItems($file, $key, $func)
    {
        $knownItems = [];
        $dumpfile = "{$this->pathToDumpfiles}/${file}";
        $handle = fopen($dumpfile, "r");
        while (!feof($handle)) {
            $line = fgets($handle);
            if ($this->ignoreLine($line)) {
                continue;
            }
            $data = $this->decodeLine($line);
            $knownItems = $func($knownItems, $data, $key);
        }

        fclose($handle);

        return $knownItems;
    }
}

