<?php

namespace App\DTO\DataHQ;

use App\Services\DataHQ\DataHQContextStateStoreInterface;

class DataHQState
{
    public string $stateContextService;
    public string $context;
    public int $contextId;

    public function __construct(string $stateContextService, string $context, int $contextId) {
        $this->stateContextService = $stateContextService;
        $this->context = $context;
        $this->contextId = $contextId;
    }

    public function getContextStateStore(): DataHQContextStateStoreInterface
    {
        $stateContextStore = app($this->stateContextService);
        $stateContextStore->setContext($this->context, $this->contextId);
        return $stateContextStore;
    }
}
