<?php

namespace App\Jobs\Datasets;

use App\Actions\Datasets\PublishDatasetAction2;
use App\Actions\Globus\GlobusApi;
use App\Models\Dataset;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PublishDatasetJob implements ShouldQueue
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
        $publishAction = new PublishDatasetAction2(GlobusApi::createGlobusApi());
        $publishAction->execute($dataset);
    }
}
