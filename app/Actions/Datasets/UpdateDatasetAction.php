<?php

namespace App\Actions\Datasets;

use App\Models\Dataset;
use App\Traits\HasTagsInRequest;
use Illuminate\Support\Facades\DB;

class UpdateDatasetAction
{
    use HasTagsInRequest;

    public function __invoke($data, Dataset $dataset)
    {
        $communities = null;
        if (array_key_exists('communities', $data)) {
            $communities = $data['communities'];
            unset($data['communities']);
        }

        $experiments = null;
        if (array_key_exists('experiments', $data)) {
            $experiments = $data['experiments'];
            unset($data['experiments']);
        }

        $this->loadTagsFromData($data);
        unset($data['tags']);

        DB::transaction(function () use ($dataset, $data, $communities, $experiments) {
            $dataset->update($data);
            $dataset->communities()->sync($communities);
            $dataset->experiments()->sync($experiments);
            $dataset->syncTags($this->tags);
        });

        return Dataset::with('communities')->where('id', $dataset->id)->first();
    }
}