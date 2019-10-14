<?php

namespace App\Actions\Datasets;

use App\Models\Dataset;

class UpdateDatasetAction
{
    public function __invoke($data, Dataset $dataset)
    {
        return tap($dataset)->update($data)->fresh();
    }
}