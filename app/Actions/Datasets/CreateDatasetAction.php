<?php

namespace App\Actions\Datasets;

use App\Models\Dataset;

class CreateDatasetAction
{
    private $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    public function __invoke($data)
    {
        $dataset = new Dataset($data);
        $dataset->owner_id = $this->userId;
        $dataset->save();
        return $dataset->fresh();
    }
}