<?php

namespace App\Http\Queries\Experiments;

use App\Models\Experiment;
use App\Traits\GetRequestParameterId;
use Illuminate\Http\Request;

class SingleExperimentQuery extends ExperimentsQueryBuilder
{
    use GetRequestParameterId;

    public function __construct(?Request $request = null)
    {
        $experimentId = $this->getParameterId('experiment');
        $query = Experiment::where('id', $experimentId);
        parent::__construct($query, $request);
    }
}