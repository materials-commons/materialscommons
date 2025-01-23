<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class CreateDataset2WebController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Project $project)
    {
        return view('app.projects.datasets.create-dataset', [
            'project' => $project,
            'dataset' => null,
        ]);
    }
}
