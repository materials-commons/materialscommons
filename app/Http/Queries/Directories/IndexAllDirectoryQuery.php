<?php

namespace App\Http\Queries\Directories;

use App\Models\File;
use Illuminate\Http\Request;

class IndexAllDirectoryQuery extends DirectoriesQueryBuilder
{
    public function __construct(?Request $request = null, $projectId)
    {
        $builder = File::with(['owner', 'directory'])
                       ->activeProjectDirectories($projectId);
        parent::__construct($builder, $request);
    }
}
