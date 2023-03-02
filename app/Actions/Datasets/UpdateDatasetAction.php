<?php

namespace App\Actions\Datasets;

use App\Models\Dataset;
use App\Models\File;
use App\Models\Paper;
use App\Traits\HasTagsInRequest;
use Illuminate\Support\Facades\DB;

class UpdateDatasetAction
{
    use HasTagsInRequest;

    public function __invoke($data, Dataset $dataset)
    {
        $projectId = $dataset->project_id;
        
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
            $papers = $this->getPapers($data['papers'], $dataset->owner_id);
            unset($data['papers']);
        }

        $existingPapers = collect();
        if (array_key_exists('existing_papers', $data)) {
            $existingPapers = collect($data['existing_papers']);
            unset($data['existing_papers']);
        }

        if (isset($data['ds_authors']) && sizeof($data['ds_authors']) != 0) {
            $data['authors'] = collect($data['ds_authors'])->implode('name', '; ');
        }

        $this->loadTagsFromData($data);
        unset($data['tags']);

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

        DB::transaction(function () use ($dataset, $data, $communities, $experiments, $papers, $existingPapers) {
            $dataset->update($data);
            $dataset->communities()->sync($communities);
            $dataset->experiments()->sync($experiments);
            $dataset->syncTags($this->tags);

            $existingPapers->each(function ($paperData, $key) {
                $p = Paper::find($key);
                if (!is_null($p)) {
                    $url = isset($paperData['url']) ? $paperData['url'] : null;
                    $p->update([
                        'name'      => $paperData['name'],
                        'reference' => $paperData['reference'],
                        'url'       => $url,
                    ]);
                }
            });
            $dataset->papers()->sync($existingPapers->keys()->all());
            $papers->each(function (Paper $paper) use ($dataset) {
                $paper->save();
                $dataset->papers()->attach($paper);
            });
        });

        return Dataset::with('communities')->where('id', $dataset->id)->first();
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