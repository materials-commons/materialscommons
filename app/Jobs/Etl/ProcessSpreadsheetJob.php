<?php

namespace App\Jobs\Etl;

use App\Imports\Etl\EntityActivityImporter;
use App\Models\File;
use App\Traits\PathForFile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;

class ProcessSpreadsheetJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, PathForFile;

    private $projectId;
    private $experimentId;
    private $userId;
    private $fileId;

    public function __construct($projectId, $experimentId, $userId, $fileId)
    {
        $this->projectId = $projectId;
        $this->experimentId = $experimentId;
        $this->userId = $userId;
        $this->fileId = $fileId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $file = File::find($this->fileId);
        $uuid = $file->uses_uuid ?? $file->uuid;
        $uuidPath = $this->getFilePathForFile($uuid);
        $importer = new EntityActivityImporter($this->projectId, $this->experimentId, $this->userId);
        Excel::import($importer, storage_path("app/{$uuidPath}"), null, \Maatwebsite\Excel\Excel::XLSX);
    }
}
