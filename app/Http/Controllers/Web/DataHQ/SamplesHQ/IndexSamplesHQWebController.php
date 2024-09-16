<?php

namespace App\Http\Controllers\Web\DataHQ\SamplesHQ;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Traits\Entities\EntityAndAttributeQueries;
use Illuminate\Http\Request;
use function view;

class IndexSamplesHQWebController extends Controller
{
    use EntityAndAttributeQueries;

    public function __invoke(Request $request, Project $project)
    {
        return view('app.projects.datahq.sampleshq.index', [
            'project'           => $project,
        ]);
    }
}
