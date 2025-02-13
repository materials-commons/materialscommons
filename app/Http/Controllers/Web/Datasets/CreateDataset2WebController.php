<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\Project;
use Illuminate\Http\Request;

class CreateDataset2WebController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Project $project)
    {
        $datasetId = $request->input('datasetId');
        $dataset = null;

        if (!is_null($datasetId)) {
            $dataset = Dataset::with(['tags', 'communities'])
                              ->where('project_id', $project->id)
                              ->whereNull('published_at')
                              ->where('id', $datasetId)
                              ->first();
        }

        // Either dataset wasn't specified in the query parameter, or the
        // dataset couldn't be found.
        if (is_null($dataset)) {
            $dataset = Dataset::create([
                'project_id' => $project->id,
                'name'       => '',
                'owner_id'   => auth()->user()->id,
            ]);
        }

        return view('app.projects.datasets.create-dataset', [
            'project' => $project,
            'dataset' => $dataset,
        ]);
    }
}
