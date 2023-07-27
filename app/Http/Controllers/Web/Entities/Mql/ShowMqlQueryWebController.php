<?php

namespace App\Http\Controllers\Web\Entities\Mql;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mql\MqlSelectionRequest;
use App\Models\Project;
use App\Models\SavedQuery;
use App\Traits\Mql\MqlQueryBuilder;

class ShowMqlQueryWebController extends Controller
{
    use MqlQueryBuilder;

    public function __invoke(MqlSelectionRequest $request, Project $project)
    {
        $validated = $request->validated();

        return view('partials.mql._mql-textbox', [
            'project' => $project,
            'query'   => $this->buildMqlQueryText($validated),
            'queries' => SavedQuery::where('owner_id', auth()->id())
                                   ->where('project_id', $project->id)
                                   ->get(),
        ]);
    }
}
