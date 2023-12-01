<?php

namespace App\Http\Controllers\Api\Etl;

use App\Actions\Activities\CreateActivityAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Etl\CreateWorkflowRequest;
use App\Models\Activity;
use App\Models\Project;
use Illuminate\Http\Request;

class CreateWorkflowApiController extends Controller
{
    private $globalCategory;
    private $experimentId;

    public function __invoke(CreateWorkflowRequest $request, CreateActivityAction $createActivity, Project $project)
    {
        $validated = $request->validated();
        $userId = auth()->id();

        $this->globalCategory = $this->getAttribute($validated, "category");
        $this->experimentId = $this->getAttribute($validated, "experiment_id");

        $createdActivities = [];

        foreach ($validated["activities"] as $a) {
            $activity = $createActivity([]);
            $createdActivities[$a["name"]] = $activity;
            if (isset($a["attributes"])) {
                $this->addAttributesToActivity($a["attributes"], $a);
            }

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

    private function getAttribute($validated, $key)
    {
        if (isset($validated[$key])) {
            return $validated[$key];
        }

        return null;
    }

    private function addAttributesToActivity($attrs, $activity)
    {
        $createActivityAction = new CreateActivityAction();
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
