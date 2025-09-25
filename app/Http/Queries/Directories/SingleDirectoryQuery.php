<?php

namespace App\Http\Queries\Directories;

use App\Models\File;
use App\Traits\GetRequestParameterId;
use Illuminate\Http\Request;

class SingleDirectoryQuery extends DirectoriesQueryBuilder
{
    use GetRequestParameterId;

    /**
     * Build query for directory
     *
     * @param  Request|null  $request
     */
    public function __construct(?Request $request = null)
    {
        $directoryId = $this->getParameterId('directory');
        $query = File::with(['owner', 'directory'])
                     ->where('id', $directoryId)
                     ->active()
                     ->directories();
        parent::__construct($query, $request);
    }
}
