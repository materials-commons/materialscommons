<?php

namespace App\Http\Controllers\Web\Mql;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mql\MqlStoreSaveQueryRequest;
use App\Models\Project;
use App\Models\SavedQuery;

class StoreMQLQueryWebController extends Controller
{
    public function __invoke(MqlStoreSaveQueryRequest $request, Project $project)
    {
        $validated = $request->validated();
        ray($validated);
        SavedQuery::create([
            'project_id'  => $project->id,
            'name'        => $validated['name'],
            'description' => $validated['description'],
            'owner_id'    => auth()->id(),
            'query'       => collect($validated)->except(['name', 'description'])->toArray(),
        ]);
        return "";
    }
}
