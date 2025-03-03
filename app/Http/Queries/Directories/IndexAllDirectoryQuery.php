<?php

namespace App\Http\Queries\Directories;

use App\Models\File;
use Illuminate\Http\Request;

class IndexAllDirectoryQuery extends DirectoriesQueryBuilder
{
    public function __construct(?Request $request = null, $projectId)
    {
        $builder = File::with(['owner', 'directory'])
                       ->where('project_id', $projectId)
                       ->where('mime_type', 'directory')
                       ->whereNull('deleted_at')
                       ->whereNull('dataset_id')
                       ->where('current', true);
        parent::__construct($builder, $request);
    }
}