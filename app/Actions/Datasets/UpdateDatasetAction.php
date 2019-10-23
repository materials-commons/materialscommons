<?php

namespace App\Actions\Datasets;

use App\Models\Dataset;
use Illuminate\Support\Facades\DB;

class UpdateDatasetAction
{
    public function __invoke($data, Dataset $dataset)
    {
        $experiments = null;
        if (array_key_exists('experiments', $data)) {
            $experiments = $data['experiments'];
            unset($data['experiments']);
        }
        DB::transaction(function () use ($dataset, $data, $experiments) {
            $dataset->update($data);
            $dataset->experiments()->sync($experiments);
        });

        return Dataset::with('experiments')->where('id', $dataset->id)->first();
    }
}