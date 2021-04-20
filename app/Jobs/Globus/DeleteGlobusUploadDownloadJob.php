<?php

namespace App\Jobs\Globus;

use App\Actions\Globus\Downloads\DeleteGlobusDownloadAction;
use App\Actions\Globus\GlobusApi;
use App\Actions\Globus\Uploads\DeleteGlobusUploadAction;
use App\Enums\GlobusType;
use App\Models\GlobusUploadDownload;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeleteGlobusUploadDownloadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public GlobusUploadDownload $globusUploadDownload;

    public function __construct(GlobusUploadDownload $globusUploadDownload)
    {
        $this->globusUploadDownload = $globusUploadDownload;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->globusUploadDownload->type == GlobusType::ProjectUpload) {
            $deleteGlobusUploadAction = new DeleteGlobusUploadAction(GlobusApi::createGlobusApi());
            $deleteGlobusUploadAction($this->globusUploadDownload);
        } else {
            $deleteGlobusDownloadAction = new DeleteGlobusDownloadAction(GlobusApi::createGlobusApi());
            $deleteGlobusDownloadAction($this->globusUploadDownload);
        }
    }
}
