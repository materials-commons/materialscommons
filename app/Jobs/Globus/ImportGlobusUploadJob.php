<?php

namespace App\Jobs\Globus;

use App\Actions\Globus\GlobusApi;
use App\Actions\Globus\Uploads\ImportGlobusUploadIntoProjectAction;
use App\Enums\GlobusStatus;
use App\Models\GlobusUploadDownload;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportGlobusUploadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // For testability these properties are public.

    /** @var GlobusUploadDownload */
    public $globusUpload;
    public $maxItemsToProcess;

    public function __construct(GlobusUploadDownload $globusUpload, $maxItemsToProcess)
    {
        $this->globusUpload = $globusUpload;
        $this->maxItemsToProcess = $maxItemsToProcess;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        ini_set("memory_limit", "4096M");
        $globusApi = GlobusApi::createGlobusApi();
        $importGlobusUploadInProjectAction = new ImportGlobusUploadIntoProjectAction($this->globusUpload,
            $this->maxItemsToProcess, $globusApi);
        $importGlobusUploadInProjectAction();
    }

    /**
     * When the job fails mark it as done so that it will be picked up and processed again.
     */
    public function failed($exception = null)
    {
        $this->globusUpload->update(['status' => GlobusStatus::Done]);
    }
}
