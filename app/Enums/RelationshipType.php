<?php


namespace App\Enums;

use BenSampo\Enum\Enum;

final class RelationshipType extends Enum
{
    const Create = 0;
    const Use = 1;
    const Transform = 2;
}