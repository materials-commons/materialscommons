<?php

namespace App\Http\Controllers\Web\Entities\Mql;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mql\MqlSelectionRequest;
use App\Models\Project;
use App\Traits\Mql\MqlQueryBuilder;

class ShowMqlQueryWebController extends Controller
{
    use MqlQueryBuilder;

    public function __invoke(MqlSelectionRequest $request, Project $project)
    {
        $validated = $request->validated();

        return view('partials.entities.mql._mql-textbox', [
            'project' => $project,
            'query'   => $this->buildMqlQueryText($validated),
        ]);
    }
}
