<?php

namespace App\Services\DataHQ;

use App\DTO\DataHQState;
use App\Models\Dataset;
use App\Models\Experiment;
use App\Models\Project;
use function session;

class SampleHQStateStore implements DataHQStateStoreInterface
{
    public function getOrCreateStateForProject(Project $project): ?DataHQState
    {
        return session("state:samplehq:p:state:{$project->id}", new DataHQState());
    }

    public function saveStateForProject(Project $project, DataHQState $state): void
    {
        session(["state:samplehq:p:state:{$project->id}" => $state]);
    }

    public function getOrCreateStateForExperiment(Experiment $experiment): ?DataHQState
    {
        return session("state:samplehq:e:state:{$experiment->id}", new DataHQState());
    }

    public function saveStateForExperiment(Experiment $experiment, DataHQState $state): void
    {
        session(["state:samplehq:e:state:{$experiment->id}" => $state]);
    }

    public function getOrCreateStateForDataset(Dataset $dataset): ?DataHQState
    {
        return session("state:samplehq:ds:state:{$dataset->id}", new DataHQState());
    }

    public function saveStateForDataset(Dataset $dataset, DataHQState $state): void
    {
        session(["state:samplehq:ds:state:{$dataset->id}" => $state]);
    }

    public function getProjectContext(Project $project): string
    {
        return session("state:samplehq:p:context:{$project->id}");
    }

    public function saveProjectContext(Project $project, string $context): void
    {
        session(["state:samplehq:p:context:{$project->id}" => $context]);
    }

    public function getDatasetContext(Dataset $dataset): string
    {
        return session("state:samplehq:ds:context:{$dataset->id}");
    }

    public function saveDatasetContext(Dataset $dataset, string $context): void
    {
        session(["state:samplehq:ds:context:{$dataset->id}" => $context]);
    }
}