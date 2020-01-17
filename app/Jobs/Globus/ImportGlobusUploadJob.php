<?php

namespace App\Jobs\Globus;

use App\Actions\Globus\Uploads\LoadGlobusUploadIntoProjectAction;
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
        $loadGlobusUploadInProjectAction = new LoadGlobusUploadIntoProjectAction($this->globusUpload,
            $this->maxItemsToProcess);
        $loadGlobusUploadInProjectAction();
    }
}
