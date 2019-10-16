<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Actions\Datasets\AssignDoiToDatasetAction;
use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\Project;

class AssignDoiWebController extends Controller
{
    public function __invoke(Project $project, Dataset $dataset)
    {
        $assignDoiToDatasetAction = new AssignDoiToDatasetAction();
        $user = auth()->user();
        $assignDoiToDatasetAction($dataset, $user);
        return back();
    }
}
