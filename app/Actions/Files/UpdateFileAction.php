<?php

namespace App\Actions\Files;

use App\Models\File;

class UpdateFileAction
{
    public function __invoke($data, File $file)
    {
        return tap($file)->update($data)->fresh();
    }
}