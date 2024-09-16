<?php

namespace App\Services\DataHQ;

use App\DTO\DataHQState;
use App\Models\Dataset;
use App\Models\Experiment;
use App\Models\Project;

interface DataHQStateStoreInterface
{
    public function getOrCreateStateForProject(Project $project): ?DataHQState;

    public function saveStateForProject(Project $project, DataHQState $state): void;

    public function getOrCreateStateForExperiment(Experiment $experiment): ?DataHQState;

    public function saveStateForExperiment(Experiment $experiment, DataHQState $state): void;

    public function getOrCreateStateForDataset(Dataset $dataset): ?DataHQState;

    public function saveStateForDataset(Dataset $dataset, DataHQState $state): void;

    public function getProjectContext(Project $project): string;

    public function saveProjectContext(Project $project, string $context): void;

    public function getDatasetContext(Dataset $dataset): string;

    public function saveDatasetContext(Dataset $dataset, string $context): void;
}