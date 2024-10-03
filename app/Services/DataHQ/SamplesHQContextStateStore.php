<?php

namespace App\Services\DataHQ;

use App\DTO\DataHQ\State;
use App\DTO\DataHQ\SubviewState;
use App\DTO\DataHQ\TabState;
use function session;

class SamplesHQContextStateStore implements DataHQContextStateStoreInterface
{
    private string $context;
    private int $contextId;
    private string $stateKey;

    public function setContext(string $context, int $contextId): void
    {
        $this->context = $context;
        $this->contextId = $contextId;
        $this->stateKey = "state:samplehq:state:{$this->context}:{$this->contextId}";
    }

    public function getOrCreateState(): ?State
    {
        return session($this->stateKey, function () {
            $state = new State();
            $ts = new TabState('All Samples', 'index');
            $subviewState = new SubviewState('All Samples', 'index', 'samples');
            $ts->subviews->push($subviewState);
            $state->tabs->push($ts);
            session([$this->stateKey => $state]);
            return $state;
        });
    }

    public function saveState(State $state): void
    {
        session([$this->stateKey => $state]);
    }
}
