<?php

namespace App\Http\Controllers\Web\Published\Datasets\Datatables;

use App\Http\Controllers\Controller;
use App\Models\Entity;
use Freshbitsweb\Laratables\Laratables;

class GetDatasetEntitiesDatatableWebController extends Controller
{
    public function __invoke($datasetId)
    {
        return Laratables::recordsOf(Entity::class, function ($query) use ($datasetId) {
            return $query->whereHas('datasets', function ($q) use ($datasetId) {
                $q->where('dataset_id', $datasetId);
            });
        });
    }
}
