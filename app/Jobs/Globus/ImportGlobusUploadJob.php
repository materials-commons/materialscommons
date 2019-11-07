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

    public function __construct(GlobusUpload $globusUpload)
    {
        $this->globusUpload = $globusUpload;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $loadGlobusUploadInProjectAction = new LoadGlobusUploadIntoProjectAction($this->globusUpload, 1000);
        $loadGlobusUploadInProjectAction();
    }
}
