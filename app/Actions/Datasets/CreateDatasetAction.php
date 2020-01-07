<?php

namespace App\Actions\Datasets;

use App\Models\Dataset;
use App\Models\Experiment;
use Illuminate\Support\Facades\DB;

class CreateDatasetAction
{
    private $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    public function __invoke($data)
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

        $tags = null;
        if (array_key_exists('tags', $data)) {
            $tags = collect($data['tags'])->map(function ($tag) {
                return $tag["value"];
            })->toArray();
            unset($data['tags']);
        }

        $dataset = new Dataset($data);
        $dataset->owner_id = $this->userId;
        $dataset->file_selection = [
            'include_files' => [],
            'exclude_files' => [],
            'include_dirs'  => [],
            'exclude_dirs'  => [],
        ];

        DB::transaction(function () use ($dataset, $communities, $experiments, $tags) {
            $dataset->save();
            if ($communities !== null) {
                $dataset->communities()->attach($communities);
            }

            if ($experiments !== null) {
                $dataset->experiments()->attach($experiments);
                foreach ($experiments as $experimentId) {
                    $experiment = Experiment::with(['activities', 'entities'])->find($experimentId);
                    $dataset->activities()->syncWithoutDetaching($experiment->activities);
                    $dataset->entities()->syncWithoutDetaching($experiment->entities);
                }
            }

            if ($tags !== null) {
                $dataset->attachTags($tags);
            }
        });

        return $dataset->fresh();
    }
}