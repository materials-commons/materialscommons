<?php

namespace App\Jobs\Datasets;

use App\Actions\Datasets\RefreshPublishedDatasetAction;
use App\Models\Dataset;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RefreshPublishedDatasetJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $dataset;

    public function __construct(Dataset $dataset)
    {
        $this->dataset = $dataset;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $refreshPublishedDatasetAction = new RefreshPublishedDatasetAction();
        $refreshPublishedDatasetAction->execute($this->dataset);
    }
}
