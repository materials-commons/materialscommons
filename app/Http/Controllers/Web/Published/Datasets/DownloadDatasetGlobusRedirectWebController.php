<?php

namespace App\Http\Controllers\Web\Published\Datasets;

use App\Actions\Globus\GlobusUrl;
use App\Http\Controllers\Controller;
use App\Models\Dataset;

class DownloadDatasetGlobusRedirectWebController extends Controller
{
    use ViewsAndDownloads;

    public function __invoke(Dataset $dataset)
    {
        $this->incrementDownloads($dataset->id);
        return redirect(GlobusUrl::globusDownloadUrl(config('globus.endpoint'),
            "/__published_datasets/{$dataset->uuid}/"));
    }
}
