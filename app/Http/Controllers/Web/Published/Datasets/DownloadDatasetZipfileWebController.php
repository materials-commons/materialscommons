<?php

namespace App\Http\Controllers\Web\Published\Datasets;

use App\Http\Controllers\Controller;
use App\Models\Dataset;

class DownloadDatasetZipfileWebController extends Controller
{
    use ViewsAndDownloads;

    public function __invoke(Dataset $dataset)
    {
        if (!auth()->check()) {
            abort(403);
        }
        $this->incrementDatasetDownloads($dataset->id);
        return response()->download($dataset->publishedZipfilePath());
    }
}
