<?php

namespace App\Http\Controllers\Web\Folders\Filter;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;
use App\Models\User;
use Freshbitsweb\Laratables\Laratables;

class DTGetFilesForUserFilterWebController extends Controller
{
    public function __invoke(Project $project, User $user)
    {
        return Laratables::recordsOf(File::class, function ($query) use ($project, $user) {
            return $query->where('project_id', $project->id)
                         ->where('owner_id', $user->id)
                         ->whereNull('dataset_id')
                         ->whereNull('deleted_at')
                         ->where('current', true)
                         ->where('mime_type', '<>', 'directory');
        });
    }
}
