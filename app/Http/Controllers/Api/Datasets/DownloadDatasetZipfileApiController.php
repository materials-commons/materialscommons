<?php

namespace App\Http\Controllers\Api\Datasets;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\Published\Datasets\ViewsAndDownloads;
use App\Models\Dataset;
use Illuminate\Http\Request;

class DownloadDatasetZipfileApiController extends Controller
{
    use ViewsAndDownloads;

    public function __invoke(Request $request, Dataset $dataset)
    {
        abort_if(is_null($dataset->published_at), 400, "No such dataset");

        $this->incrementDownloads($dataset->id);
        return response()->download($dataset->zipfilePath());
    }
}
