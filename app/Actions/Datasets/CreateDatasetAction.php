<?php

namespace App\Actions\Datasets;

use App\Models\Dataset;
use App\Models\Experiment;
use App\Traits\HasTagsInRequest;
use Illuminate\Support\Facades\DB;

class CreateDatasetAction
{
    use HasTagsInRequest;

    public function execute($data, $projectId, $userId)
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

        $mcAuthors = null;
        if (array_key_exists('mc_authors', $data)) {
            $mcAuthors = $data['mc_authors'];
            unset($data['mc_authors']);
        }

        $this->loadTagsFromData($data);
        unset($data['tags']);

        $dataset = new Dataset($data);
        $dataset->owner_id = $userId;
        $dataset->project_id = $projectId;
        $dataset->file_selection = [
            'include_files' => [],
            'exclude_files' => [],
            'include_dirs'  => [],
            'exclude_dirs'  => [],
        ];

        DB::transaction(function () use ($dataset, $communities, $experiments, $mcAuthors) {
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

            $dataset->authors()->syncWithoutDetaching($mcAuthors);

            $dataset->attachTags($this->tags);
        });

        return $dataset->fresh();
    }
}