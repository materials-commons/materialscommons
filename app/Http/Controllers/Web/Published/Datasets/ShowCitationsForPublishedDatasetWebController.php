<?php

namespace App\Http\Controllers\Web\Published\Datasets;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use Illuminate\Http\Request;

class ShowCitationsForPublishedDatasetWebController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Dataset $dataset)
    {
        //
    }
}
