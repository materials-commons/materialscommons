<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\Project;

class DownloadDatasetZipfileWebController extends Controller
{
    public function __invoke(Project $project, Dataset $dataset)
    {
        return response()->download($dataset->zipfilePath());
    }
}
