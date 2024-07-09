<?php

namespace App\Jobs\Datasets\Citations;

use App\Actions\Datasets\GetAndSavePublishedDatasetCitationsAction;
use App\Models\Dataset;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Storage;
use function config;
use function is_null;
use const JSON_PRETTY_PRINT;

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
        $getAndSavePublishedDatasetCitationsAction = new GetAndSavePublishedDatasetCitationsAction();
        $cursor = Dataset::with('papers')
                         ->whereDoesntHave('tags', function ($q) {
                             $q->where('tags.id', config('visus.import_tag_id'));
                         })
                         ->whereNotNull('published_at')
                         ->cursor();
        foreach ($cursor as $ds) {
            $getAndSavePublishedDatasetCitationsAction->execute($ds);
        }
    }
}
