<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\Project;

class ShowImportDatasetIntoProjectWebController extends Controller
{
    public function __invoke(Project $p, Dataset $dataset)
    {
        abort_unless(auth()->id() == $p->owner_id, 403);
        return view('app.projects.datasets.import-dataset', [
            'dataset' => $dataset,
            'project' => $p,
        ]);
    }
}
