<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class GlobusStatus extends Enum
{
    const Done = 0;
    const Loading = 1;
    const Uploading = 2;
    const NotStarted = 3;
}
