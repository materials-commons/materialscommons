<?php

namespace App\Imports\Etl;

use App\Actions\Activities\CreateActivityAction;
use App\Actions\Entities\CreateEntityAction;
use App\Actions\Etl\GetFileByPathAction;
use App\Enums\ExperimentStatus;
use App\Models\Activity;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Entity;
use App\Models\EntityState;
use App\Models\Experiment;
use App\Models\File;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Row;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EntityActivityImporter
{
    const GLOBAL_WORKSHEET_NAME = 'mc constants';

    private int $projectId;
    private ?int $experimentId;

    private ?Experiment $experiment;
    private int $userId;

    private bool $sawHeader = false;
    private ?HeaderTracker $headerTracker;
    private ?Worksheet $worksheet;
    private EntityTracker $entityTracker;
    private HashedActivityTracker $activityTracker;
    private int $rowNumber;
    private Collection $rows;
    private Collection $currentSheetRows;
    private GetFileByPathAction $getFileByPathAction;
    private GlobalSettingsLoader $globalSettings;
    private int $currentSheetPosition;
    private EtlState $etlState;

    private static array $ignoreWorksheetKeys = [
        "i"       => true,
        "ignore"  => true,
        "doc"     => true,
        "example" => true,
    ];

    private static array $experimentWorksheetKeys = [
        "e"          => true,
        "exp"        => true,
        "experiment" => true,
    ];

    public function __construct($projectId, $experimentId, $userId, EtlState $etlState)
    {
        $this->projectId = $projectId;
        $this->experimentId = $experimentId;
        $this->userId = $userId;
        $this->rowNumber = 0;
        $this->rows = collect();
        $this->currentSheetRows = collect();
        $this->currentSheetPosition = 1;
        $this->experiment = null;

        $this->entityTracker = new EntityTracker();
        $this->activityTracker = new HashedActivityTracker();
        $this->getFileByPathAction = new GetFileByPathAction();
        $this->globalSettings = new GlobalSettingsLoader();
        $this->etlState = $etlState;
    }

    public function execute($spreadsheetPath)
    {
        if (!is_null($this->experimentId)) {
            $this->experiment = Experiment::findOrFail($this->experimentId);
        }
        $spreadsheet = $this->loadSpreadsheet($spreadsheetPath);
        $this->loadGlobalSettingsIfExists($spreadsheet);
        $this->processWorksheets($spreadsheet);
        $this->afterImport();
    }

    private function loadSpreadsheet($path)
    {
        $reader = new Xlsx();
        $reader->setReadEmptyCells(true);
        $reader->setReadDataOnly(false);
        return $reader->load($path);
    }

    private function loadGlobalSettingsIfExists(Spreadsheet $spreadsheet)
    {
        $worksheet = $spreadsheet->getSheetByName(self::GLOBAL_WORKSHEET_NAME);
        if (is_null($worksheet)) {
            return;
        }
        $this->globalSettings->loadGlobalWorksheet($worksheet);
    }

    private function processWorksheets(Spreadsheet $spreadsheet)
    {
        $this->currentSheetPosition = 1;
        $worksheets = $spreadsheet->getAllSheets();
        $this->etlState->etlRun->n_sheets = sizeof($worksheets);
        foreach ($worksheets as $worksheet) {
            if ($worksheet->getTitle() == self::GLOBAL_WORKSHEET_NAME) {
                // Skip processing the global worksheet as the sheet has already been processed
                // and is used to hold settings that apply across sheets. A "normal" worksheet
                // contains samples for the processing step, while the global worksheet contains
                // settings to apply with processing steps.
                continue;
            }

            if ($this->ignoreWorksheet($worksheet)) {
                continue;
            }

            if ($this->isExperimentWorksheet($worksheet)) {
                $this->switchToNewExperiment($worksheet);
            }

            $this->processWorksheet($worksheet);
            $this->etlState->etlRun->n_sheets_processed++;
            $this->currentSheetPosition++;
        }
    }

    private function ignoreWorksheet(Worksheet $worksheet): bool
    {
        return $this->worksheetContainsKeyFrom($worksheet, self::$ignoreWorksheetKeys);
    }

    private function isExperimentWorksheet(Worksheet $worksheet): bool
    {
        return $this->worksheetContainsKeyFrom($worksheet, self::$experimentWorksheetKeys);
    }

    private function worksheetContainsKeyFrom(Worksheet $worksheet, $keys): bool
    {
        $worksheetTitleLower = Str::lower($worksheet->getTitle());
        $dash = strpos($worksheetTitleLower, '-');

        // If there was a dash then the user might have set the sheet to be ignored. Check if the word
        // before the dash is one of the keywords we use that tells us to ignore the sheet.
        if ($dash !== false) {
            $prefix = substr($worksheetTitleLower, 0, $dash);
            if (array_key_exists($prefix, $keys)) {
                return true;
            }
        }

        // Second way of handling this if the first failed - with parenthesis. For example if a user
        // were to give a worksheet the name "(Example) How to use", then it would be recognized
        // as an example worksheet and ignored.
        $leftParen = strpos($worksheetTitleLower, '(');
        $rightParen = strpos($worksheetTitleLower, ')');
        if ($leftParen !== false && $rightParen !== false) {
            $prefix = substr($worksheetTitleLower, $leftParen + 1, $rightParen - 1);
            return array_key_exists($prefix, $keys);
        }

        return false;
    }

    private function worksheetContainsKeyUsingDashFrom(Worksheet $worksheet, $keys): bool
    {
        $worksheetTitleLower = Str::lower($worksheet->getTitle());
        $dash = strpos($worksheetTitleLower, '-');

        // If there was a dash then the user might have set the sheet to be ignored. Check if the word
        // before the dash is one of the keywords we use that tells us to ignore the sheet.
        if ($dash !== false) {
            $prefix = substr($worksheetTitleLower, 0, $dash);
            return array_key_exists($prefix, $keys);
        }

        return false;
    }

    private function worksheetContainsKeyUsingParensFrom(Worksheet $worksheet, $keys): bool
    {
        $worksheetTitleLower = Str::lower($worksheet->getTitle());
        // Second way of handling this if the first failed - with parenthesis. For example if a user
        // were to give a worksheet the name "(Example) How to use", then it would be recognized
        // as an example worksheet and ignored.
        $leftParen = strpos($worksheetTitleLower, '(');
        $rightParen = strpos($worksheetTitleLower, ')');
        if ($leftParen !== false && $rightParen !== false) {
            $prefix = substr($worksheetTitleLower, $leftParen + 1, $rightParen - 1);
            return array_key_exists($prefix, $keys);
        }

        return false;
    }

    private function switchToNewExperiment(Worksheet $worksheet)
    {
        if (!is_null($this->experiment)) {
            // There was a previous experiment so connect all the relationships
            // before we start cleaning up state.
            $this->createActivityRelationships();
        }
        $this->experiment = $this->createExperiment($worksheet);
        $this->experimentId = $this->experiment->id;

        // Reset loader state
        $this->currentSheetPosition = 1;
        $this->rows = collect();
        $this->entityTracker = new EntityTracker();
        $this->activityTracker = new HashedActivityTracker();
    }

    private function createExperiment(Worksheet $worksheet): ?Experiment
    {
        $name = $this->getAnnotatedWorksheetName($worksheet);
        return Experiment::create([
            'name'       => $name,
            'project_id' => $this->projectId,
            'owner_id'   => $this->userId,
            'status'     => ExperimentStatus::InProgress,
        ]);
    }

    private function getAnnotatedWorksheetName(Worksheet $worksheet): string
    {
        $title = $worksheet->getTitle();
        if ($this->worksheetContainsKeyUsingDashFrom($worksheet, self::$experimentWorksheetKeys)) {
            $dash = strpos($title, '-');
            return Str::of(substr($title, $dash + 1))->trim()->__toString();
        }

        // Must have parens so
        $parenRight = strpos($title, ')');
        return Str::of(substr($title, $parenRight + 1))->trim()->__toString();
    }

    private function processWorksheet(Worksheet $worksheet)
    {
        $blankRowCount = 0;
        $this->beforeSheet($worksheet);
        $title = $worksheet->getTitle();
        $this->etlState->logProgress("\nProcessing worksheet {$title}");
        foreach ($worksheet->getRowIterator() as $row) {
            if (!$this->onRow($row)) {
                // saw blank row
                $blankRowCount++;
            } else {
                // Not a blank row so reset blankRowCount
                $blankRowCount = 0;
            }
            if ($blankRowCount >= 10) {
                // when we've seen 10 or more consecutive blank rows then
                // we stop processing data
                break;
            }
        }
        $this->afterSheet();
    }

    private function beforeSheet($worksheet)
    {
        $this->sawHeader = false;
        $this->headerTracker = new HeaderTracker();
        $this->worksheet = $worksheet;
        $this->rowNumber = 0;
        $this->currentSheetRows = collect();
    }

    private function afterSheet()
    {
        $this->currentSheetRows->each(function (RowTracker $row) {
            if (!$this->entityTracker->hasEntity($row->entityName)) {
                $this->addNewEntity($row);
            } else {
                $this->addToExistingEntity($row);
            }
        });
    }

    private function afterImport()
    {
        // All sheets processed and loaded, now build relationships
        // from parent column.
        $this->createActivityRelationships();
        $this->etlState->done();
    }

    private function onRow(Row $row)
    {
        if (!$this->sawHeader) {
            $this->processHeader($row);
            return true;
        } else {
            return $this->processSample($row);
        }
    }

    public function getHeaders()
    {
        return $this->headerTracker;
    }

    private function processHeader(Row $row)
    {
        $index = 0;
        $cellIterator = $row->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(true);
        foreach ($cellIterator as $cell) {
            if ($index > 1) {
                $value = $cell->getValue();
                if ($value !== null) {
                    $ah = AttributeHeader::parse($value);
                    $this->headerTracker->addHeader($ah);
                }
            }
            $index++;
        }

        $this->sawHeader = true;
    }

    // Process a sample in the worksheet
    private function processSample(Row $row)
    {
        $rowTracker = new RowTracker($this->rowNumber, $this->worksheet->getTitle());
        $rowTracker->loadRow($row, $this->headerTracker);
        if ($rowTracker->entityName == "") {
            return false;
        }
        $this->rows->push($rowTracker);
        $this->currentSheetRows->push($rowTracker);
        return true;
    }

    private function addNewEntity(RowTracker $row)
    {
        $createEntityAction = new CreateEntityAction();
        $entity = $createEntityAction([
            'name'          => $row->entityName,
            'project_id'    => $this->projectId,
            'experiment_id' => $this->experimentId,
        ], $this->userId);
        $this->etlState->logProgress("   Adding sample: {$entity->name}");
        $this->entityTracker->addEntity($entity);
        $this->etlState->etlRun->n_entities++;

        /** @var EntityState $state */
        $state = $entity->entityStates()->first();
        $this->addAttributesToEntity($row->entityAttributes, $entity, $state, $row);
        // Add a new activity
        $activity = $this->addNewActivity($entity, $state, $row);
        $this->activityTracker->addActivity($row->activityAttributesHash, $activity);
        $this->addFilesToActivityAndEntity($row->fileAttributes, $entity, $activity);
    }

    private function addAttributesToEntity(
        Collection $entityAttributes,
        Entity $entity,
        EntityState $state,
        RowTracker $rowTracker
    ) {
        $seenAttributes = collect();
        $attributePosition = 1;
        $entityAttributes->each(function ($attr) use ($state, $entity, $seenAttributes, &$attributePosition) {
            if ($seenAttributes->has($attr->name)) {
                $a = $seenAttributes->get($attr->name);
                AttributeValue::create([
                    'attribute_id' => $a->id,
                    'unit'         => $attr->unit,
                    'val'          => ['value' => $attr->value],
                ]);
            } else {
                $a = Attribute::create([
                    'name'              => $attr->name,
                    'attributable_id'   => $state->id,
                    'eindex'            => $attributePosition,
                    'attributable_type' => EntityState::class,
                ]);
                AttributeValue::create([
                    'attribute_id' => $a->id,
                    'unit'         => $attr->unit,
                    'val'          => ['value' => $attr->value],
                ]);
                $seenAttributes->put($attr->name, $a);
                $attributePosition++;
            }
        });
        $globalAttributes = $this->globalSettings->getGlobalSettingsForWorksheet($rowTracker->activityName);
        foreach ($globalAttributes as $globalAttribute) {
            if ($globalAttribute->attributeHeader->attrType === "entity") {
                $a = Attribute::create([
                    'name'              => $globalAttribute->attributeHeader->name,
                    'attributable_id'   => $state->id,
                    'eindex'            => $attributePosition,
                    'attributable_type' => EntityState::class,
                ]);
                AttributeValue::create([
                    'attribute_id' => $a->id,
                    'unit'         => $globalAttribute->attributeHeader->unit,
                    'val'          => ['value' => $globalAttribute->value],
                ]);
                $attributePosition++;
            }
        }
    }

    private function addFilesToActivityAndEntity(Collection $fileAttributes, ?Entity $entity, ?Activity $activity)
    {
        $fileAttributes->each(function (ColumnAttribute $attr) use ($entity, $activity) {
            $header = $this->headerTracker->getHeaderByIndex($attr->columnNumber - 2);

            // Multiple files can be specified in a cell when they are separated by a semi-colon (;), eg
            // file1.txt;file2.txt
            foreach (explode(";", $attr->value) as $value) {
                $value = trim($value);

                if ($value == "") {
                    continue;
                }

                $path = "{$header->name}/{$value}";
                if (strpos($value, "/") !== false) {
                    // Cell contains the path, no need to use the header
                    $path = $value;
                }

                if ($this->isWildCard($path)) {
                    $this->addWildCardFiles($path, $entity, $activity);
                    continue;
                }

                if (($dir = $this->isDirectory($path)) !== null) {
                    $this->addDirectoryFiles($dir, $entity, $activity);
                    continue;
                }

                $this->addSingleFile($path, $entity, $activity);
            }
        });
    }

    private function isWildCard($path)
    {
        return Str::contains($path, ['*', '?']);
    }

    private function addWildCardFiles($path, ?Entity $entity, ?Activity $activity = null)
    {
        $dirPath = dirname($path);
        $expression = basename($path);
        $dir = File::where('path', $dirPath)
            ->where('current', true)
            ->whereNull('deleted_at')
            ->where('project_id', $this->projectId)
            ->first();

        if (is_null($dir)) {
            $this->etlState->logError("   Unable to find directory {$dirPath}");
            return;
        }

        File::where('directory_id', $dir->id)
            ->where('mime_type', '<>', 'directory')
            ->whereNull('deleted_at')
            ->where('current', true)
            ->chunk(100, function ($files) use ($entity, $activity, $expression) {
                $files->each(function (File $file) use ($entity, $activity, $expression) {
                    if (!fnmatch($expression, $file->name)) {
                        return;
                    }

                    $this->addFileToActivityAndEntity($file, $activity, $entity);
                });
            });
    }

    private function isDirectory($path)
    {
        $dir = $this->getFileByPathAction->execute($this->projectId, $path);
        return optional($dir)->mime_type == "directory" ? $dir : null;
    }

    private function addDirectoryFiles(File $dir, ?Entity $entity, ?Activity $activity)
    {
        File::where('directory_id', $dir->id)
            ->where('mime_type', '<>', 'directory')
            ->whereNull('deleted_at')
            ->where('current', true)
            ->chunk(100, function ($files) use ($entity, $activity, $dir) {
                $files->each(function (File $file) use ($entity, $activity, $dir) {
                    if (!$this->addFileToActivityAndEntity($file, $activity, $entity)) {
                        $dirPath = $dir->getDirPathForFormatting();
                        $this->etlState->logError("   Unable to find file {$dirPath}/{$file->name}");
                    }
                });
            });
    }

    private function addSingleFile($path, ?Entity $entity, ?Activity $activity)
    {
        $file = $this->getFileByPathAction->execute($this->projectId, $path);
        if (is_null($file)) {
            $this->etlState->logError("   Unable to find file {$path}");
            return;
        }
        $this->addFileToActivityAndEntity($file, $activity, $entity);

        $this->experiment->files()->syncWithoutDetaching($file);
    }

    private function addFileToActivityAndEntity(?File $file, ?Activity $activity, ?Entity $entity): bool
    {
        if (is_null($file)) {
            return false;
        }

        $this->addFileToActivity($file, $activity);
        $this->addFileToEntity($file, $entity);
        return true;
    }

    private function addFileToActivity(?File $file, ?Activity $activity)
    {
        if (is_null($file)) {
            return;
        }

        if (is_null($activity)) {
            return;
        }

        $activity->files()->attach([$file->id => ['direction' => 'in']]);
    }

    private function addFileToEntity(?File $file, ?Entity $entity)
    {
        if (is_null($file)) {
            return;
        }

        if (is_null($entity)) {
            return;
        }

        $entity->files()->syncWithoutDetaching([$file->id]);
    }

    /*
     * if (Str::of($attr->value)->contains(['*', '?'])) {
     *    matchOnRegex();
     *    return;
     * }
     *
     * matchOnFileOrDir();
     */

    private function addToExistingEntity(RowTracker $row)
    {
        // 1. Create a new entity state and add it to the entity
        // 2. Add all entity attributes to that entity state
        // 3. Create the new activity and associate it with that entity/entity state.
        $entity = $this->entityTracker->getEntity($row->entityName);
        $state = EntityState::create([
            'owner_id'  => $this->userId,
            'entity_id' => $entity->id,
            'current'   => true,
        ]);
        $this->addAttributesToEntity($row->entityAttributes, $entity, $state, $row);
        $activity = $this->addNewActivity($entity, $state, $row);
        $this->activityTracker->addActivity($row->activityAttributesHash, $activity);
        $this->addFilesToActivityAndEntity($row->fileAttributes, $entity, $activity);
    }

    private function addNewActivity(Entity $entity, EntityState $entityState, RowTracker $rowTracker)
    {
        $createActivityAction = new CreateActivityAction();
        $attributePosition = 1;
        $attributes = $rowTracker->activityAttributes->map(function (ColumnAttribute $attr) use (&$attributePosition) {
            return [
                'name'   => $attr->name,
                'value'  => $attr->value,
                'unit'   => $attr->unit,
                'eindex' => $attributePosition++,
            ];
        })->toArray();

        // Add global settings for worksheet (activity)
        $globalAttributes = $this->globalSettings->getGlobalSettingsForWorksheet($rowTracker->activityName);
        foreach ($globalAttributes as $globalAttribute) {
            if ($globalAttribute->attributeHeader->attrType === "activity") {
                array_push($attributes, [
                    'name'   => $globalAttribute->attributeHeader->name,
                    'unit'   => $globalAttribute->attributeHeader->unit,
                    'value'  => $globalAttribute->value,
                    'eindex' => $attributePosition++,
                ]);
            }
        }

        $activity = $createActivityAction([
            'name'          => $rowTracker->activityName,
            'project_id'    => $this->projectId,
            'experiment_id' => $this->experimentId,
            'attributes'    => $attributes,
            'eindex'        => $this->currentSheetPosition,
        ], $this->userId);

        $this->etlState->logProgress("   Adding process: {$activity->name} for sample {$entity->name}");
        $activity->entities()->attach($entity);
        $activity->entityStates()->attach([$entityState->id => ['direction' => 'out']]);
        $this->etlState->etlRun->n_activities++;

        return $activity;
    }

    private function createActivityRelationships()
    {
        $this->rows
            ->filter(function (RowTracker $row) {
                return $row->relatedActivityName !== "";
            })
            ->each(function (RowTracker $row) {
                $activity = $this->activityTracker->getActivity($row->activityAttributesHash);
                // Hook up all the activities who have an entityId === $entityId and that have a name === $row->relatedActivityName
                // by loop through each of these activities and doing the following: (entity state is the entity state from the
                // $activity for the given $entity
                $entity = $activity->entities()->where('name', $row->entityName)->first();
                $entityActivities = $entity->activities()->where('name', $row->relatedActivityName)->get();
                $entityActivities->each(function ($ea) use ($entity, $activity) {
                    $entityState = $ea->entityStates()->where('entity_id', $entity->id)
                        ->where('direction', 'out')
                        ->first();
                    $activity->entityStates()->attach($entityState, ['direction' => 'in']);
                });
            });
    }
}
