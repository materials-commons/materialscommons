<?php

namespace App\Http\Controllers\Web\Activities\Datatables;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Freshbitsweb\Laratables\Laratables;

class GetProjectActivitiesDatatableWebController extends Controller
{
    public function __invoke($projectId)
    {
        return Laratables::recordsOf(Activity::class, function ($query) use ($projectId) {
            return $query->where('project_id', $projectId);
        });
    }
}
