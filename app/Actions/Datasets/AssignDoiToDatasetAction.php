<?php

namespace App\Actions\Datasets;

use App\Helpers\DOIHelpers;
use App\Models\Dataset;

class AssignDoiToDatasetAction
{
    public function __invoke(Dataset $dataset, $user, $publishAsTestDataset = false)
    {
        if ($publishAsTestDataset) {
            if (empty($dataset->test_doi)) {
                $doi = DOIHelpers::mintDOI($dataset->name, $user->name, $dataset->id, $publishAsTestDataset);
                return tap($dataset)->update(['test_doi' => $doi])->fresh();
            }
        } else {
            if (empty($dataset->doi)) {
                $doi = DOIHelpers::mintDOI($dataset->name, $user->name, $dataset->id, $publishAsTestDataset);
                return tap($dataset)->update(['doi' => $doi])->fresh();
            }
        }

        return $dataset;
    }
}
