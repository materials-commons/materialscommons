<?php

namespace App\Actions\Migration;

use App\Models\Project;

class ImportProjects extends AbstractImporter
{
    use ItemCreater;

    private $projectsToLoad;
    private $projectsToIgnore;

    public function __construct($pathToDumpfiles, $ignoreExisting)
    {
        parent::__construct($pathToDumpfiles, "projects", $ignoreExisting);
        $this->projectsToLoad = collect();
        $this->projectsToIgnore = collect();
    }

    protected function setup()
    {
    }

    public function setProjectsToLoad($projectsToLoad)
    {
        $this->projectsToLoad = collect($projectsToLoad)->flip();
        return $this;
    }

    public function setProjectsToIgnore($projectsToIgnore)
    {
        $this->projectsToIgnore = collect($projectsToIgnore)->flip();
        return $this;
    }

    protected function loadData($data)
    {
        if (!$this->loadProject($data['id'])) {
            return null;
        }

        $modelData = $this->createCommonModelData($data);
        if ($modelData == null) {
            return null;
        }

        $modelData['is_active'] = true;
//        echo "Adding project {$modelData['name']}\n";

        return Project::create($modelData);
    }

    private function loadProject($uuid)
    {
        if (!$this->projectsToIgnore->isEmpty()) {
            if ($this->projectsToIgnore->has($uuid)) {
                // projectsToIgnore list is not empty and we found an entry.
                return false;
            }
        }

        // If we are here then projectsToIgnore list is either empty, or it didn't contain that uuid,
        // so now check if there are projects that we should only load.

        if ($this->projectsToLoad->isEmpty()) {
            // If projects list is empty then no filter is taking place and return
            // true to load the project.
            return true;
        }

        return $this->projectsToLoad->has($uuid);
    }

    protected function cleanup()
    {
    }
}