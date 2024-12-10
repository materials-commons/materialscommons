<?php

namespace App\DTO\DataHQ;

use Illuminate\Support\Collection;
use JsonSerializable;

class OverviewState implements jsonSerializable
{
    public string $projectContext;
    public Collection $experimentContext;
    public Collection $datasetContext;

    public function __construct(string $projectContext, Collection $experimentContext, Collection $datasetContext)
    {
        $this->projectContext = $projectContext;
        $this->experimentContext = $experimentContext;
        $this->datasetContext = $datasetContext;
    }

    public function jsonSerialize(): array
    {
        return [
            'projectContext'    => $this->projectContext,
            'experimentContext' => $this->experimentContext->toArray(),
            'datasetContext'    => $this->datasetContext->toArray(),
        ];
    }
}