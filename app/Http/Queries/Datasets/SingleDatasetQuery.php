<?php

namespace App\Http\Queries\Datasets;

use App\Models\Dataset;
use App\Traits\GetRequestParameterId;
use Illuminate\Http\Request;

class SingleDatasetQuery extends DatasetsQueryBuilder
{
    use GetRequestParameterId;

    public function __construct(?Request $request = null)
    {
        $datasetId = $this->getParameterId('dataset');
        $query = Dataset::findOrFail($datasetId)->query();
        parent::__construct($query, $request);
    }
}