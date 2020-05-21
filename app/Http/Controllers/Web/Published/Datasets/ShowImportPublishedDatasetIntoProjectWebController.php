<?php

namespace App\Http\Controllers\Web\Published\Datasets;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\Project;

class ShowImportPublishedDatasetIntoProjectWebController extends Controller
{
    public function __invoke(Dataset $dataset, Project $p)
    {
        abort_unless(auth()->id() == $p->owner_id, 403);
        return view('public.datasets.import-dataset', [
            'dataset' => $dataset,
            'project' => $p,
        ]);
    }
}
