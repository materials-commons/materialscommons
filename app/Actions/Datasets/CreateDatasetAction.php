<?php

namespace App\Actions\Datasets;

use App\Models\Dataset;
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
        $dataset = new Dataset($data);
        $dataset->owner_id = $this->userId;
        $dataset->file_selection = [
            'include_files' => [],
            'exclude_files' => [],
            'include_dirs'  => [],
            'exclude_dirs'  => [],
        ];

        DB::transaction(function () use ($dataset, $communities) {
            $dataset->save();
            if ($communities !== null) {
                $dataset->communities()->attach($communities);
            }
        });

        return $dataset->fresh();
    }
}