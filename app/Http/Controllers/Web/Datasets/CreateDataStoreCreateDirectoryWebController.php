<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Actions\Directories\CreateDirectoryAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Directories\CreateDirectoryRequest;
use App\Models\Dataset;
use App\Models\Project;

class CreateDataStoreCreateDirectoryWebController extends Controller
{
    public function __invoke(CreateDirectoryRequest $request, CreateDirectoryAction $createDirectoryAction,
        Project $project, Dataset $dataset)
    {
        $validated = $request->validated();
        $directory = $createDirectoryAction->execute($validated, auth()->id());
        return redirect(route('projects.datasets.create-data', [$project, $dataset, $directory]));
    }
}
