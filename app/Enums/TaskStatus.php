<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class TaskStatus extends Enum
{
    const Open = 0;
    const Closed = 1;
    const Hold = 2;
}
