<?php

namespace App\Jobs\Datasets;

use App\Actions\Datasets\UnpublishDatasetAction;
use App\Models\Dataset;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UnpublishDatasetJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $datasetId;

    public function __construct($datasetId)
    {
        $this->datasetId = $datasetId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $dataset = Dataset::findOrFail($this->datasetId);
        $unpublishDatasetAction = new UnpublishDatasetAction();
        $unpublishDatasetAction($dataset);
    }
}
