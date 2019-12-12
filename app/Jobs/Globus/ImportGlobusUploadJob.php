<?php

namespace App\Jobs\Globus;

use App\Actions\Globus\LoadGlobusUploadIntoProjectAction;
use App\Models\GlobusUpload;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportGlobusUploadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var GlobusUpload */
    private $globusUpload;
    private $maxItemsToProcess;

    public function __construct(GlobusUpload $globusUpload, $maxItemsToProcess)
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
