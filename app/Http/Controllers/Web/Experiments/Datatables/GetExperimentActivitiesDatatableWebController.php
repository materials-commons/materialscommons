<?php

namespace App\Http\Controllers\Web\Experiments\Datatables;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Freshbitsweb\Laratables\Laratables;

class GetExperimentActivitiesDatatableWebController extends Controller
{
    public function __invoke($projectId, $experimentId)
    {
        return Laratables::recordsOf(Activity::class, function ($query) use ($experimentId) {
            return $query->whereHas('experiments', function ($q) use ($experimentId) {
                $q->where('experiment_id', $experimentId);
            });
        });
    }
}
