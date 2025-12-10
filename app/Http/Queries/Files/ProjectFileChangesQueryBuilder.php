<?php

namespace App\Http\Queries\Files;

use App\Models\File;
use Illuminate\Http\Request;

class ProjectFileChangesQueryBuilder extends FilesQueryBuilder
{
    public function __construct($projectId, $since, ?Request $request = null)
    {
        $query = File::query()
                     ->with(['owner', 'directory'])
                     ->where('created_at', '>', $since)
                     ->where('project_id', $projectId)
                     ->active()
                     ->files()
                     ->orderBy('created_at');
        parent::__construct($query, $request);
    }
}
