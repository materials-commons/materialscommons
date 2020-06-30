<?php

namespace App\Actions\Datasets;

use App\Models\Dataset;
use Illuminate\Support\Facades\DB;

class DeleteDatasetAction
{
    public function execute(Dataset $dataset)
    {
        DB::transaction(function () use ($dataset) {
            $dataset->files()->delete();
            $dataset->delete();
        });
    }
}