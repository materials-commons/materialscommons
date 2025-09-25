<?php

namespace App\Http\Queries\Directories;

use App\Models\File;
use Illuminate\Http\Request;

class IndexDirectoryQuery extends DirectoriesQueryBuilder
{
    public function __construct(?Request $request = null, $projectId, $directoryId)
    {
        $builder = File::with(['owner', 'directory'])
                       ->where('project_id', $projectId)
                       ->where('directory_id', $directoryId)
                       ->active();
        parent::__construct($builder, $request);
    }
}
