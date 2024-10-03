<?php

namespace App\Services\DataHQ;

use App\DTO\DataHQ\DataHQState;

class DataHQStateStore
{
    private const stateKey = 'datahqstate';

    public static function getState(): DataHQState
    {
        return session(static::stateKey);
    }

    public static function saveState(DataHQState $dataHQState): void
    {
        session([static::stateKey => $dataHQState]);
    }
}
