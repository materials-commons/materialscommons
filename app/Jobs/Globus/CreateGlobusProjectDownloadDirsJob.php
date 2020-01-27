<?php

namespace App\Jobs\Globus;

use App\Actions\Globus\Downloads\CreateGlobusProjectDownloadDirsAction;
use App\Actions\Globus\GlobusApi;
use App\Models\GlobusUploadDownload;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateGlobusProjectDownloadDirsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $globusDownload;
    public $user;

    public function __construct(GlobusUploadDownload $globusDownload, $user)
    {
        $this->globusDownload = $globusDownload;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $globusApi = GlobusApi::createGlobusApi();
        $createGlobusProjectDownloadDirsAction = new CreateGlobusProjectDownloadDirsAction($globusApi);
        $createGlobusProjectDownloadDirsAction($this->globusDownload, $this->user);
    }
}
