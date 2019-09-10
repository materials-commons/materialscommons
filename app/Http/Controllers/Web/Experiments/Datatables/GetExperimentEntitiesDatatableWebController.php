<?php

namespace App\Http\Controllers\Web\Experiments\Datatables;

use App\Http\Controllers\Controller;
use App\Models\Entity;
use Freshbitsweb\Laratables\Laratables;

class GetExperimentEntitiesDatatableWebController extends Controller
{
    public function __invoke($projectId, $experimentId)
    {
        return Laratables::recordsOf(Entity::class, function ($query) use ($experimentId) {
            return $query->whereHas('experiments', function ($q) use ($experimentId) {
                $q->where('experiment_id', $experimentId);
            });
        });
    }
}
