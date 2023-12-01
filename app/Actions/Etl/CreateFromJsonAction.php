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

        foreach ($data["activities"] as $a) {
            if (isset($a["attributes"])) {
                $this->addAttributesToActivity($a["attributes"], $a);
            }

            if (isset($a["files"])) {
                $this->addFilesToActivity($a["files"], $a);
            }

            if (isset($a["entities"])) {
                $this->addEntitiesToActivity($a["entities"], $a);
            }
        }

        if (isset($validated["entities"])) {
            $this->addEntitiesToExistingActivities($validated["entities"], $createdActivities);
        }
    }

    private function addAttributesToActivity($attrs, $activity)
    {
        $createActivityAction = new CreateActivityAction();
        $createActivityAction([
            'name' => $activity['name'],
        ]);
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