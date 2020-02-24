<?php

namespace App\Actions\Migration;

use Illuminate\Support\Carbon;

class Datadirs2ndTime
{
}

class MigrateRethinkdbDataAction
{
    use LineHandler;

    private $orderToProcessObjectDumpFiles = [
        ['users.json' => ImportUsers::class],
        ['projects.json' => ImportProjects::class],
        ['experiments.json' => ImportExperiments::class],
        ['datadirs.json' => ImportDirectories::class],
        ['datadirs.json' => ImportDirectoriesMatchParents::class],
        ['datafiles.json' => ImportFiles::class],
        ['samples.json' => ImportEntities::class],
        ['propertysets.json' => ImportEntityStates::class],
        ['processes.json' => ImportActivities::class],
        ['setupproperties.json' => ImportActivitySettings::class],
        ['properties.json' => ImportEntityStateAttributes::class],
        ['measurements.json' => ImportEntityStateAttributeValues::class],
        ['datasets.json' => ImportDatasets::class],
        ['views.json' => ImportDatasetViews::class],
    ];

    private $pathToDumpFiles;
    private $ignoreExisting;
    private $filesToLoad;

    public function __construct($pathToDumpFiles, $ignoreExisting, $filesToLoad)
    {
        $this->pathToDumpFiles = $pathToDumpFiles;
        $this->ignoreExisting = $ignoreExisting;
        $this->filesToLoad = collect($filesToLoad)->flip();
    }

    public function __invoke($projectsToIgnore, $projectsToLoad)
    {
        $startedAt = Carbon::now()->setTimezone('America/Detroit')->toTimeString();

        foreach ($this->orderToProcessObjectDumpFiles as $dumpFile) {
            $file = key($dumpFile);
            if (!$this->shouldLoadFile($file)) {
                continue;
            }
            $importerClass = $dumpFile[$file];
            $pathToDumpFiles = $this->pathToDumpFiles;
            if ($importerClass == ImportDatasetViews::class) {
                $pathToDumpFiles = $this->pathToDumpFiles."/../mcpub";
            }

            $importer = new $importerClass($pathToDumpFiles, $this->ignoreExisting);

            if ($file == "projects.json") {
                $importer->setProjectsToIgnore($projectsToIgnore);
                $importer->setProjectsToLoad($projectsToLoad);
            }

            if (!$importer->loadDumpfile($file)) {
                return;
            }

            $this->loadCacheForFile($file);
        }
        $finishedAt = Carbon::now()->setTimezone('America/Detroit')->toTimeString();
        echo "\nMigration started at: {$startedAt}\n";
        echo "Migration completed at: {$finishedAt}\n";
    }

    private function shouldLoadFile($file)
    {
        if ($this->filesToLoad->isEmpty()) {
            return true;
        }

        return $this->filesToLoad->has($file);
    }

    private function loadCacheForFile($file)
    {
        switch ($file) {
            case "users.json":
                ItemCache::loadUsers();
                break;
            case "projects.json":
                ItemCache::loadProjects();
                break;
            case "processes.json":
                ItemCache::loadActivities();
                break;
            case "samples.json":
                ItemCache::loadEntities();
                break;
            case "experiments.json":
                ItemCache::loadExperiments();
                break;
            case "propertysets.json":
                ItemCache::loadEntityStates();
                break;
        }
    }
}