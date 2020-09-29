<?php

namespace App\Actions\Datasets;

use App\Models\Dataset;
use App\Models\Paper;
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

        $this->loadTagsFromData($data);
        unset($data['tags']);

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
}