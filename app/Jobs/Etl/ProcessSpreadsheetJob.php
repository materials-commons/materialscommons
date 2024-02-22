<?php

namespace App\Jobs\Etl;

use App\Imports\Etl\EntityActivityImporter;
use App\Imports\Etl\EtlState;
use App\Mail\SpreadsheetLoadFinishedMail;
use App\Models\Experiment;
use App\Models\File;
use App\Models\Project;
use App\Models\User;
use App\Traits\GoogleSheets;
use App\Traits\PathForFile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use function uniqid;

class ProcessSpreadsheetJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, PathForFile;
    use GoogleSheets;

    private $projectId;
    private $experimentId;
    private $userId;
    private $fileId;
    private $sheetUrl;

    public function __construct($projectId, $experimentId, $userId, $fileId, $sheetUrl)
    {
        $this->projectId = $projectId;
        $this->experimentId = $experimentId;
        $this->userId = $userId;
        $this->fileId = $fileId;
        $this->sheetUrl = $this->cleanupGoogleSheetUrl($sheetUrl);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        ini_set("memory_limit", "4096M");
        if (!blank($this->sheetUrl)) {
            $fileName = $this->downloadSheetAndReturnFileName();
            $filePath = $this->getPathToSheet($fileName);
            $file = null;
            $etlState = new EtlState($this->userId, null);
        } else {
            $fileName = "";
            $file = File::find($this->fileId);
            $uuidPath = $this->getFilePathForFile($file);
            $filePath = Storage::disk('mcfs')->path("{$uuidPath}");
            $etlState = new EtlState($this->userId, $file->id);
        }

        $experiment = Experiment::findOrFail($this->experimentId);
        $experiment->etlruns()->save($etlState->etlRun);
        $experiment->update([
            'loading_started_at' => Carbon::now(),
            'job_id'             => $this->job->getJobId(),
        ]);
        $importer = new EntityActivityImporter($this->projectId, $this->experimentId, $this->userId, $etlState);
        $importer->execute($filePath);
        $experiment->update([
            'loading_finished_at' => Carbon::now(),
            'job_id'              => null,
        ]);
        Mail::to(User::findOrFail($this->userId))
            ->send(new SpreadsheetLoadFinishedMail($file, $this->sheetUrl, Project::findOrFail($this->projectId),
                $experiment,
                $etlState->etlRun));
        if (!is_null($this->sheetUrl)) {
            // need to delete the temporary file.
            Storage::disk('mcfs')->delete('__sheets/'.$fileName);
        }
    }

    private function downloadSheetAndReturnFileName(): string
    {
        $filename = uniqid().'.xlsx';
        @Storage::disk('mcfs')->makeDirectory('__sheets');
        $filePath = Storage::disk('mcfs')->path('__sheets/'.$filename);

        // Since this is an url we need to download it.
        $command = "curl -o \"{$filePath}\" -L {$this->sheetUrl}/export?format=xlsx";
        $process = Process::fromShellCommandline($command);
        $process->run();

        return $filename;
    }

    private function getPathToSheet($filename): string
    {
        return Storage::disk('mcfs')->path('__sheets/'.$filename);
    }
}
