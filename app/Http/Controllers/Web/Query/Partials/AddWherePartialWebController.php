<?php

namespace App\Http\Controllers\Web\Query\Partials;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class AddWherePartialWebController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Project $project, $attrName, $attrType, $lastId)
    {
        return view('partials.query._add-where', [
            'project'  => $project,
            'attrName' => $attrName,
            'attrType' => $attrType,
            'lastId'   => $lastId,
        ]);
    }
}
