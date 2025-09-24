<?php

namespace App\Imports\Etl;

use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Worksheet\Row;

class RowTracker
{
    public $entityOrActivityName;
    public $activityName;
    public $activityType;
    public $relatedActivityName;
    public $activityAttributes;
    public $entityAttributes;
    public $fileAttributes;
    public $activityAttributesHash;
    public $rowNumber;

    public $entityTags;
    public $activityTags;

    private static $blankCellKeywords = [
        'n/a'   => true,
        'blank' => true,
    ];

    public function __construct(int $rowNumber, $sheetName)
    {
        $this->rowNumber = $rowNumber;
        $this->entityOrActivityName = "";
        $this->activityName = $sheetName;
        $this->activityType = $sheetName;
        $this->relatedActivityName = "";
        $this->activityAttributes = collect();
        $this->entityAttributes = collect();
        $this->fileAttributes = collect();
        $this->activityAttributesHash = "";
        $this->entityTags = collect();
        $this->activityTags = collect();
    }

    public function loadRow(Row $row, HeaderTracker $headerTracker)
    {
        $index = 0;
        $cellIterator = $row->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(false);

        foreach ($cellIterator as $cell) {
            $value = Str::trim($this->getCellValue($cell));
            if ($this->isBlankCell($value)) {
                $index++;
                continue;
            }

            if ($index === 0) {
                // We are processing the first column. At this point we could be reading either an activity
                // or a sample name. We don't know whether this stands for the name of an entity, in which
                // case we are processing a sample centric worksheet, or an activity, in which case we are
                // processing a process centric worksheet. How this field is used will be determined later.
                $this->entityOrActivityName = $value;
            } elseif ($index === 1) {
                $header = $headerTracker->getHeaderByIndex($index - 1);
                if (is_null($header) || Str::lower($header->name) == "parent") {
                    $this->relatedActivityName = $value;
                    $index++;
                    continue;
                }

                $this->handleAttributeValue($header, $value, $index, $cell->getCoordinate());
            } else {
                $header = $headerTracker->getHeaderByIndex($index - 1);

                if (is_null($header)) {
                    // Break out of loop when looking up a header that doesn't exist. That means the
                    // cell contains a value, but there is no associated header.
                    break;
                }

                if (!$this->handleAttributeValue($header, $value, $index, $cell->getCoordinate())) {
                    $index++;
                    continue;
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
        hash_update($ctx, $this->entityOrActivityName);

        $this->activityAttributesHash = hash_final($ctx);
    }

    private function handleAttributeValue($header, $value, $index, $cellCoordinates)
    {
        if ($header->attrType === "ignore" || $header->attrType === "unknown" || Str::lower($header->name) == "parent") {
            return false;
        }

        $colAttr = new ColumnAttribute($header->name, $value, $header->unit, $header->attrType, $index, $cellCoordinates,
            $header->important);
        switch ($header->attrType) {
            case "entity":
                $this->entityAttributes->push($colAttr);
                break;
            case "tags-entity":
                $colAttr->addTags($value);
                $this->entityTags->push($colAttr);
                break;
            case "activity":
                $this->activityAttributes->push($colAttr);
                break;
            case "tags-activity":
                $colAttr->addTags($value);
                $this->activityTags->push($colAttr);
                break;
            case "file":
                $this->fileAttributes->push($colAttr);
                break;
            case "calculation":
                $this->activityName = $value;
                break;
        }
        return true;
    }

    private function getCellValue(Cell $cell): string
    {
        $value = $cell->getFormattedValue();

        $dataType = $cell->getDataType();
        if ($dataType == DataType::TYPE_FORMULA) {
            $raw = $cell->getValue();
            if ($raw == "=TRUE()") {
                $value = "TRUE";
            } elseif ($raw == "=FALSE()") {
                $value = "FALSE";
            }
        } elseif ($dataType == DataType::TYPE_BOOL) {
            if ($value == "1") {
                $value = "TRUE";
            } elseif ($value == "0") {
                $value = "FALSE";
            } elseif ($value == "") {
                $value = "FALSE";
            }
        }

        return $value;
    }

    private function isBlankCell($value): bool
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
