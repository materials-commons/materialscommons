<?php

namespace App\Actions\Migration;

use App\Models\Project;
use App\Models\User;

class ImportProjects extends AbstractImporter
{
    use ItemCreater;
    use ItemLoader;

    private $projectsToLoad;
    private $projectsToIgnore;
    private $project2users;

    public function __construct($pathToDumpfiles, $ignoreExisting)
    {
        parent::__construct($pathToDumpfiles, "projects", $ignoreExisting);
        $this->projectsToLoad = collect();
        $this->projectsToIgnore = collect();
    }

    protected function setup()
    {
        $this->project2users = $this->loadItemMappingMultiple("access.json", "project_id", "user_id");
    }

    protected function cleanup()
    {
        $this->project2users = null;
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

    protected function getModelClass()
    {
        return Project::class;
    }

    protected function shouldLoadRelationshipsOnSkip()
    {
        return true;
    }

    /**
     * @param  \App\Models\Project  $project
     */
    protected function loadRelationships($project)
    {
        $project->users()->syncWithoutDetaching($this->getProjectUsers($project->uuid));
    }

    private function getProjectUsers($uuid)
    {
        return ItemCache::loadItemsFromMultiple($this->project2users, $uuid, function ($email) {
            $user = User::where('email', $email)->first();
            return $user->id ?? null;
        });
    }
}