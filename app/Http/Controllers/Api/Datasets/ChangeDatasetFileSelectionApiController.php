<?php

namespace App\Http\Controllers\Api\Datasets;

use App\Http\Controllers\Controller;
use App\Http\Requests\Datasets\ChangeFileSelectionRequest;
use App\Http\Resources\Datasets\DatasetResource;
use App\Models\Dataset;
use App\Models\Project;

class ChangeDatasetFileSelectionApiController extends Controller
{
    public function __invoke(ChangeFileSelectionRequest $request, Project $project, Dataset $dataset)
    {
        $validated = $request->validated();
        $dataset->update([
            'file_selection' => [
                'include_files' => $validated['include_files'],
                'exclude_files' => $validated['exclude_files'],
                'include_dirs'  => $validated['include_dirs'],
                'exclude_dirs'  => $validated['exclude_dirs'],
            ],
        ]);

        return new DatasetResource($dataset);
    }
}
