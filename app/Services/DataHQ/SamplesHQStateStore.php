<?php

namespace App\Services\DataHQ;

use App\DTO\DataHQ\State;
use App\DTO\DataHQ\TabState;
use App\Models\Dataset;
use App\Models\Experiment;
use App\Models\Project;
use function session;

class SamplesHQStateStore implements DataHQStateStoreInterface
{
    public function getOrCreateStateForProject(Project $project): ?State
    {
        return session("state:samplehq:p:state:{$project->id}", function () use ($project) {
            $state = new State();
            $state->tabs->push(new TabState('All Samples', 'all-samples'));
            session(["state:samplehq:p:state:{$project->id}" => $state]);
            return $state;
        });
    }

    public function saveStateForProject(Project $project, State $state): void
    {
        session(["state:samplehq:p:state:{$project->id}" => $state]);
    }

    public function getOrCreateStateForExperiment(Experiment $experiment): ?State
    {
        return session("state:samplehq:e:state:{$experiment->id}", new State());
    }

    public function saveStateForExperiment(Experiment $experiment, State $state): void
    {
        session(["state:samplehq:e:state:{$experiment->id}" => $state]);
    }

    public function getOrCreateStateForDataset(Dataset $dataset): ?State
    {
        return session("state:samplehq:ds:state:{$dataset->id}", new State());
    }

    public function saveStateForDataset(Dataset $dataset, State $state): void
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