<?php

namespace App\DTO\DataHQOld;

use App\Services\DataHQ\DataHQContextStateStoreInterface;

class DataHQUIState
{
    public ?DataHQState $data = null;
    public ?DataHQContextStateStoreInterface $stateStoreService;


}
