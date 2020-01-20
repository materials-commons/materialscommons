<?php

namespace App\Actions\Migration;

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
        ['processes.json' => ImportActivities::class],
        ['setupproperties.json' => ImportActivitySettings::class],
        ['propertysets.json' => ImportEntityStates::class],
        ['properties.json' => ImportEntityStateAttributes::class],
        ['measurements.json' => ImportEntityStateAttributeValues::class],
        ['datasets.json' => ImportDatasets::class],
    ];

    private $pathToDumpFiles;
    private $ignoreExisting;

    public function __construct($pathToDumpFiles, $ignoreExisting)
    {
        $this->pathToDumpFiles = $pathToDumpFiles;
        $this->ignoreExisting = $ignoreExisting;
    }

    public function __invoke($projectsToIgnore, $projectsToLoad)
    {
        foreach ($this->orderToProcessObjectDumpFiles as $dumpFile) {
            $file = key($dumpFile);
            $importerClass = $dumpFile[$file];
            $importer = new $importerClass($this->pathToDumpFiles, $this->ignoreExisting);

            if ($file == "projects.json") {
                $importer->setProjectsToIgnore($projectsToIgnore);
                $importer->setProjectsToLoad($projectsToLoad);
            }

            if (!$importer->loadDumpfile($file)) {
                return;
            }

            if ($file == "users.json") {
                ItemCache::loadUsers();
            } elseif ($file == "projects.json") {
                ItemCache::loadProjects();
            }
        }
    }
}