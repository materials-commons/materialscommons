<?php

namespace App\Http\Controllers\Web\Published\Datasets;

use App\Http\Controllers\Controller;
use App\Models\Dataset;

class DownloadDatasetZipfileWebController extends Controller
{
    public function __invoke(Dataset $dataset)
    {
        return response()->download($dataset->zipfilePath());
    }
}
