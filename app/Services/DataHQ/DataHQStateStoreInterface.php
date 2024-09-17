<?php

namespace App\Services\DataHQ;

use App\DTO\DataHQ\State;
use App\Models\Dataset;
use App\Models\Experiment;
use App\Models\Project;

interface DataHQStateStoreInterface
{
    public function getOrCreateStateForProject(Project $project): ?State;

    public function saveStateForProject(Project $project, State $state): void;

    public function getOrCreateStateForExperiment(Experiment $experiment): ?State;

    public function saveStateForExperiment(Experiment $experiment, State $state): void;

    public function getOrCreateStateForDataset(Dataset $dataset): ?State;

    public function saveStateForDataset(Dataset $dataset, State $state): void;

    public function getProjectContext(Project $project): string;

    public function saveProjectContext(Project $project, string $context): void;

    public function getDatasetContext(Dataset $dataset): string;

    public function saveDatasetContext(Dataset $dataset, string $context): void;
}