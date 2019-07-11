<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class UserType extends Enum
{
    const Unknown = 0;
    const Faculty = 1;
    const Staff = 2;
    const Researcher = 3;
    const Intern = 4;
    const Undergrad = 5;
    const PhDStudent = 6;
    const GraduateStudent = 7;
    const PostDoc = 8;
}
