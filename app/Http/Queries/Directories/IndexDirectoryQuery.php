<?php

namespace App\Http\Queries\Directories;

use App\Models\File;
use Illuminate\Http\Request;

class IndexDirectoryQuery extends DirectoriesQueryBuilder
{
    public function __construct(?Request $request = null, $projectId, $directoryId)
    {
        $builder = File::where('project_id', $projectId)
                       ->where('directory_id', $directoryId)
                       ->where('current', true);
        parent::__construct($builder, $request);
    }
}