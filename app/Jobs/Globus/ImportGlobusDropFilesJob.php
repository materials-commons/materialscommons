<?php

namespace App\Jobs\Globus;

use App\Actions\Globus\GlobusApi;
use App\Actions\Projects\ImportFilesIntoProjectAtLocationAction;
use App\Models\GlobusDrop;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ImportGlobusDropFilesJob implements ShouldQueue
{
    use Queueable;

    public $drop;
    public $dirPath;

    /**
     * Create a new job instance.
     */
    public function __construct(GlobusDrop $drop, string $dirPath)
    {
        $this->drop = $drop;
        $this->dirPath = $dirPath;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        ini_set("memory_limit", "4096M");
        $importToLocation = new ImportFilesIntoProjectAtLocationAction();
        $this->drop->update(['status' => 'importing']);
        $project = $this->drop->project;
        $owner = $this->drop->owner;
        $location = "__globus_uploads/drops/{$this->drop->id}/{$this->drop->name}";
        $importToLocation->execute($project, 'mcfs', $location, $owner, null, $this->dirPath);
        $this->drop->update(['status' => 'imported - cleaning up']);
        //        $this->drop->delete();

//        Storage::disk('mcfs')->deleteDirectory("__globus_uploads/drops/{$this->drop->id}");
    }
}
