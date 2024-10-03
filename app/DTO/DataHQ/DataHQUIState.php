<?php

namespace App\DTO\DataHQ;

use App\Services\DataHQ\DataHQContextStateStoreInterface;

class DataHQUIState
{
    public ?DataHQState $data = null;
    public ?DataHQContextStateStoreInterface $stateStoreService;


}
