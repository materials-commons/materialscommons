<?php

namespace App\Actions\Datasets;

use App\Models\Dataset;
use Illuminate\Support\Facades\DB;

class UpdateDatasetAction
{
    public function __invoke($data, Dataset $dataset)
    {
        $communities = null;
        if (array_key_exists('communities', $data)) {
            $communities = $data['communities'];
            unset($data['communities']);
        }
        DB::transaction(function () use ($dataset, $data, $communities) {
            $dataset->update($data);
            $dataset->communities()->sync($communities);
        });

        return Dataset::with('communities')->where('id', $dataset->id)->first();
    }
}