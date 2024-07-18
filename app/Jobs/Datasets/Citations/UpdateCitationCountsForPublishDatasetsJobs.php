<?php

namespace App\Jobs\Datasets\Citations;

use App\Actions\Datasets\UpdateCitationCountsForPublishedDatasetsAction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateCitationCountsForPublishDatasetsJobs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $updatePublishedDatasetsCitations = new UpdateCitationCountsForPublishedDatasetsAction();
        $updatePublishedDatasetsCitations->execute();
    }
}
