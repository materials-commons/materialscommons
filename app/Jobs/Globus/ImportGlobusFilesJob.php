<?php

namespace App\Jobs\Globus;

use App\Actions\Globus\ImportGlobusFilesIntoProjectAction;
use App\Models\GlobusRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportGlobusFilesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var \App\Models\GlobusRequest
     */
    private $globusRequest;

    public function __construct(GlobusRequest $globusRequest)
    {
        $this->globusRequest = $globusRequest;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        ini_set("memory_limit", "4096M");
        $importFilesAction = new ImportGlobusFilesIntoProjectAction();
        $importFilesAction->execute($this->globusRequest);
        $this->globusRequest->delete();
    }
}
