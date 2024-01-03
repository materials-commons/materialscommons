<?php

namespace App\Actions\Etl;

use App\Actions\Activities\CreateActivityAction;
use App\Models\Activity;

class CreateFromJsonAction
{
    private array $createdActivities;

    private CreateActivityAction $createActivityAction;

    public function __construct()
    {
        $this->createdActivities = [];
        $this->createActivityAction = new CreateActivityAction();
    }

    public function execute($data, $projectId, $userId)
    {
        $this->globalCategory = $this->getAttribute($data, "category");
        $this->experimentId = $this->getAttribute($data, "experiment_id");

        $createdActivities = [];
        $createActivityAction = new CreateActivityAction();

        foreach ($data["activities"] as $a) {
            if (!is_null($this->experimentId)) {
                $a["experiment_id"] = $this->experimentId;
            }

            $a["project_id"] = $projectId;

            $activity = $createActivityAction($a, $userId);
            $createdActivities[$activity->name] = $activity;

            if (isset($a["files"])) {
                $this->addFilesToActivity($a["files"], $activity);
            }

            if (isset($a["entities"])) {
                $this->addEntitiesToActivity($a["entities"], $activity);
            }
        }

        if (isset($validated["entities"])) {
            $this->addEntitiesToExistingActivities($validated["entities"], $createdActivities);
        }
    }

    private function addFilesToActivity($files, Activity $activity)
    {

    }

    private function addEntitiesToActivity($entities, Activity $activity)
    {

    }

    private function addEntitiesToExistingActivities(array $entities, array $createdActivities)
    {

    }
}