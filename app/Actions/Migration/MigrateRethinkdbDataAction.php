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
//        ['properties.json' => ImportEntityStateAttributes::class],
        //        ['measurements.json' => AttributeValue::class],
        //        ['datasets.json' => Dataset::class]
        // Need propertysets here...
    ];

    private $pathToDumpFiles;

    public function __construct($pathToDumpFiles)
    {
        $this->pathToDumpFiles = $pathToDumpFiles;
    }

    public function __invoke()
    {
        foreach ($this->orderToProcessObjectDumpFiles as $dumpFile) {
            $file = key($dumpFile);
            $importerClass = $dumpFile[$file];
            $importer = new $importerClass($this->pathToDumpFiles);
            if (!$importer->loadDumpfile($file)) {
                return;
            }
        }
    }
}