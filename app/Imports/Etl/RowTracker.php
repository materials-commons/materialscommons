<?php

namespace App\Imports\Etl;

use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Worksheet\Row;

//use Maatwebsite\Excel\Row;

class RowTracker
{
    public $entityName;
    public $activityName;
    public $relatedActivityName;
    public $activityAttributes;
    public $entityAttributes;
    public $fileAttributes;
    public $activityAttributesHash;
    public $rowNumber;

    private static $blankCellKeywords = [
        'n/a'   => true,
        'blank' => true,
    ];

    public function __construct(int $rowNumber, $sheetName)
    {
        $this->rowNumber = $rowNumber;
        $this->entityName = "";
        $this->activityName = $sheetName;
        $this->relatedActivityName = "";
        $this->activityAttributes = collect();
        $this->entityAttributes = collect();
        $this->fileAttributes = collect();
        $this->activityAttributesHash = "";
    }

    public function loadRow(Row $row, HeaderTracker $headerTracker)
    {
        $index = 0;
        $cellIterator = $row->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(false);

        foreach ($cellIterator as $cell) {
            $value = $cell->getFormattedValue();
            if ($this->isBlankCell($value)) {
                $index++;
                continue;
            }

            if ($index === 0) {
                $this->entityName = $value;
            } elseif ($index === 1) {
                $this->relatedActivityName = $value;
            } else {
                $header = $headerTracker->getHeaderByIndex($index - 2);
                if ($header->attrType === "ignore" || $header->attrType === "unknown") {
                    $index++;
                    continue;
                }
                $colAttr = new ColumnAttribute($header->name, $value, $header->unit, $header->attrType, $index);
                switch ($header->attrType) {
                    case "entity":
                        $this->entityAttributes->push($colAttr);
                        break;
                    case "activity":
                        $this->activityAttributes->push($colAttr);
                        break;
                    case "file":
                        $this->fileAttributes->push($colAttr);
                        break;
                }
            }

            $index++;
        }

        $ctx = hash_init("md5");
        $this->activityAttributes->each(function ($val) use ($ctx) {
            hash_update($ctx, $val->value);
        });

        // To ensure that hashes will be unique both within and across sheets add the sheet name
        // to the strings to compute the hash on.
        hash_update($ctx, $this->activityName);

        // Add in the entityName to make sure there aren't collisions in the same sheet across different entities
        hash_update($ctx, $this->entityName);

        $this->activityAttributesHash = hash_final($ctx);
    }

    private function isBlankCell($value)
    {
        if (is_null($value)) {
            return true;
        }

        if ($value === "") {
            return true;
        }

        return array_key_exists(Str::lower($value), RowTracker::$blankCellKeywords);
    }
}