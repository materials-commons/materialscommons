<?php

namespace App\Http\Controllers\Web\Entities\Datatables;

use App\Http\Controllers\Controller;
use App\Models\Entity;
use Freshbitsweb\Laratables\Laratables;

class GetProjectEntitiesDatatableWebController extends Controller
{
    public function __invoke($projectId)
    {
        return Laratables::recordsOf(Entity::class, function ($query) use ($projectId) {
            return $query->where('project_id', $projectId);
        });
    }
}
