<?php

namespace App\Services\DataHQ;

use App\DTO\DataHQ\State;
use App\Models\Dataset;
use App\Models\Experiment;
use App\Models\Project;

interface DataHQContextStateStoreInterface
{
    public const SAMPLESHQ_STATE_KEY = 'sampleshq';
    public const COMPUTATIONSHQ_STATE_KEY = 'computationshq';
    public const PROCESSESHQ_STATE_KEY = 'processeshq';

    public function setContext(string $context, int $contextId): void;

    public function getOrCreateState(): ?State;

    public function saveState(State $state): void;
}
