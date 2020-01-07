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

        $experiments = null;
        if (array_key_exists('experiments', $data)) {
            $experiments = $data['experiments'];
            unset($data['experiments']);
        }

        $tags = [];
        if (array_key_exists('tags', $data)) {
            $tags = collect($data['tags'])->map(function ($tag) {
                return $tag["value"];
            })->toArray();
            unset($data['tags']);
        }

        DB::transaction(function () use ($dataset, $data, $communities, $experiments, $tags) {
            $dataset->update($data);
            $dataset->communities()->sync($communities);
            $dataset->experiments()->sync($experiments);
            $dataset->syncTags($tags);
        });

        return Dataset::with('communities')->where('id', $dataset->id)->first();
    }
}