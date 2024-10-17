<?php

namespace App\Actions\Datasets;

use App\Models\Dataset;
use App\Models\Experiment;
use App\Models\File;
use App\Models\Paper;
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

        $papers = collect();
        if (array_key_exists('papers', $data)) {
            $papers = $this->getPapers($data['papers'], $userId);
            unset($data['papers']);
        }

        $this->loadTagsFromData($data);
        unset($data['tags']);

        if (isset($data['ds_authors']) && sizeof($data['ds_authors']) != 0) {
            $data['authors'] = collect($data['ds_authors'])->implode('name', '; ');
        }

        $dataset = new Dataset($data);
        $dataset->owner_id = $userId;
        $dataset->project_id = $projectId;
        $dataset->file_selection = [
            'include_files' => [],
            'exclude_files' => [],
            'include_dirs'  => [],
            'exclude_dirs'  => [],
        ];

        $dataset->zipfile_size = 0;
        $dataset->globus_path_exists = false;

        if (isset($data['file1_id']) && $this->fileInProject($data['file1_id'], $projectId)) {
            $dataset->file1_id = $data['file1_id'];
        }

        if (isset($data['file2_id']) && $this->fileInProject($data['file2_id'], $projectId)) {
            $dataset->file2_id = $data['file2_id'];
        }

        if (isset($data['file3_id']) && $this->fileInProject($data['file3_id'], $projectId)) {
            $dataset->file3_id = $data['file3_id'];
        }

        if (isset($data['file4_id']) && $this->fileInProject($data['file4_id'], $projectId)) {
            $dataset->file4_id = $data['file4_id'];
        }

        if (isset($data['file5_id']) && $this->fileInProject($data['file5_id'], $projectId)) {
            $dataset->file5_id = $data['file5_id'];
        }

        DB::transaction(function () use ($dataset, $communities, $experiments, $papers) {
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

            $papers->each(function (Paper $paper) use ($dataset) {
                $paper->save();
                $dataset->papers()->attach($paper);
            });

            $dataset->attachTags($this->tags);
        });

        return $dataset->fresh();
    }

    private function getPapers($papers, $userId)
    {
        return collect($papers)->map(function ($paper) use ($userId) {
            $paper['owner_id'] = $userId;
            return new Paper($paper);
        })->values();
    }

    private function fileInProject($fileId, $projectId): bool
    {
        $count = File::where('project_id', $projectId)
                     ->where('id', $fileId)
                     ->whereNull('deleted_at')
                     ->whereNull('dataset_id')
                     ->count();
        return $count != 0;
    }
}