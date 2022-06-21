<?php

namespace App\Http\Controllers\Api\Projects;

use App\Http\Controllers\Controller;
use App\Http\Resources\Files\FileResource;
use App\Models\File;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectFileChangesSinceApiController extends Controller
{
    public function __invoke(Request $request, Project $project)
    {
        $validatedData = $request->validate([
            'since' => 'date_format:Y-m-d H:i:s'
        ]);
//        return FileResource::collection(File::query()
//                                            ->with(['owner', 'directory'])
//                                            ->where('created_at', '>', $validatedData['since'])
//                                            ->where('project_id', $project->id)
//                                            ->whereNull('deleted_at')
//                                            ->where('current', true)
//                                            ->whereNull('dataset_id')
//                                            ->where('mime_type', '<>', 'directory')
//                                            ->limit(20)
//                                            ->orderBy('created_at')
//                                            ->get());

        return File::query()
                   ->with(['owner', 'directory'])
                   ->where('created_at', '>', $validatedData['since'])
                   ->where('project_id', $project->id)
                   ->whereNull('deleted_at')
                   ->where('current', true)
                   ->whereNull('dataset_id')
                   ->where('mime_type', '<>', 'directory')
                   ->orderBy('created_at')
                   ->jsonPaginate();
    }
}
