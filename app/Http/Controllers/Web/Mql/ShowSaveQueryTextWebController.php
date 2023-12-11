<?php

namespace App\Http\Controllers\Web\Mql;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mql\MqlSelectionRequest;
use App\Models\Project;
use App\Models\SavedQuery;
use App\Traits\Mql\MqlQueryBuilder;
use function auth;

class ShowSaveQueryTextWebController extends Controller
{
    use MqlQueryBuilder;

    public function __invoke(MqlSelectionRequest $request, Project $project)
    {
        $validated = $request->validated();
//        return "<span>Modal here</span>";

        $query = $this->buildMqlQueryText($validated);
        return $query;
//        return "<textarea class='form-control' name='query_text' id='query-text'>{$query}</textarea>";

//        return view('app.dialogs.save-query-dialog', [
//            'project' => $project,
//            'query'   => $this->buildMqlQueryText($validated),
//            'queries' => SavedQuery::where('owner_id', auth()->id())
//                                   ->where('project_id', $project->id)
//                                   ->get(),
//        ]);
    }
}
