<?php

namespace App\Jobs\Datasets\Unpublish;

use App\Models\Activity;
use App\Models\Dataset;
use App\Models\Entity;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeleteDatasetRelationshipsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 10;
    public Dataset $dataset;

    public function __construct(Dataset $dataset)
    {
        $this->dataset = $dataset;
    }

    public function handle()
    {
        Activity::where('dataset_id', $this->dataset->id)->delete();
        Entity::where('dataset_id', $this->dataset->id)->delete();
//        $this->dataset->entities()->delete();
//        $this->dataset->activities()->delete();
    }
}
