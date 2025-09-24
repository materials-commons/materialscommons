<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use Illuminate\Http\Request;

class ShowDatasetByDoiWebController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, string $doi)
    {
        $dataset = Dataset::with('project')
                          ->whereLike('doi', "%$doi")
                          ->orWhereLike('test_doi', "%$doi")
                          ->first();

        if (is_null($dataset)) {
            abort(404);
        }

        if (!$this->isPublished($dataset)) {
            if (!auth()->check()) {
                abort(404);
            }

            // Dataset isn't published, and the user is logged in so redirect to the project dataset page and let
            // it handle whether the user can see the dataset or not.
            return redirect(route('projects.datasets.show', [$dataset->project, $dataset]));
        }

        // If we are here, then the data is published so redirect to the published dataset page.
        return redirect(route('public.datasets.show', [$dataset]));
    }

    private function isPublished($dataset)
    {
        return !is_null($dataset->published_at) || !is_null($dataset->test_published_at);
    }
}
