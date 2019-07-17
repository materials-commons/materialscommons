<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ExperimentStatus extends Enum
{
    const InProgress = 0;
    const OnHold = 1;
    const Done = 2;
}
