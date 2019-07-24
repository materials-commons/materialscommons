<?php


namespace App\Enums;

use BenSampo\Enum\Enum;

final class ActionType extends Enum
{
    const Creates = 0;
    const Uses = 1;
    const Transforms = 2;
}