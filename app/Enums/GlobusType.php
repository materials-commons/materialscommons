<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class GlobusType extends Enum
{
    const ProjectUpload = 0;
    const ProjectDownload = 1;
    const DatasetDownload = 2;
}
