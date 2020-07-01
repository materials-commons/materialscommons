<?php

namespace App\Http\Queries\Traits;

use App\Models\File;

trait GetFileQuery
{
    public function getBaseFileQuery()
    {
        return File::with('directory')
                   ->withCount(['entityStates', 'activities', 'entities', 'experiments', 'previousVersions']);
    }

    public function findOrFail($fileId)
    {
        return $this->getBaseFileQuery()->findOrFail($fileId);
    }
}

