<?php

namespace App\Http\Controllers\Api\Datasets;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\Project;
use Illuminate\Http\Request;

class DeleteDatasetApiController extends Controller
{
    public function __invoke(Request $request, Project $project, Dataset $dataset)
    {
        abort_unless(is_null($dataset->published_at), 400, "Cannot delete a published dataset");
        abort_unless(is_null($dataset->doi), 400, "Cannot delete a dataset that has a DOI assigned");
        $dataset->delete();
    }
}
