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
use Throwable;
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
    private $etlRunId;

    public function __construct($projectId, $experimentId, $userId, $fileId, $sheetUrl, $etlRunId)
    {
        $this->projectId = $projectId;
        $this->experimentId = $experimentId;
        $this->userId = $userId;
        $this->fileId = $fileId;
        $this->sheetUrl = $this->cleanupGoogleSheetUrl($sheetUrl);
        $this->etlRunId = $etlRunId;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws Throwable
     */
    public function handle()
    {
        ini_set("memory_limit", "4096M");

        $fileName = "";
        $file = null;
        $experiment = Experiment::findOrFail($this->experimentId);
        $etlState = EtlState::fromRunId($this->etlRunId);

        try {
            if (!blank($this->sheetUrl)) {
                $etlState->setSource('google_sheet', "Google Sheet", $this->sheetUrl);
            } else {
                $file = File::findOrFail($this->fileId);
                $etlState->setSource('spreadsheet', $file->name, $file->fullPath());
            }

//            $experiment->etlruns()->save($etlState->etlRun);

            $etlState->completeStep('queued', 'Worker picked up import job.');
            $etlState->progress(3, 'Worker picked up import job.');

            $experiment->update([
                'loading_started_at' => Carbon::now(),
                'job_id'             => $this->job->getJobId(),
            ]);

            $etlState->startStep('read_spreadsheet', 'Preparing spreadsheet source.');

            if (!blank($this->sheetUrl)) {
                $etlState->progress(5, 'Downloading Google Sheet.');
                $fileName = $this->downloadSheetAndReturnFileName();
                $filePath = $this->getPathToSheet($fileName);
                $etlState->progress(12, 'Google Sheet downloaded.');
            } else {
                $etlState->progress(5, 'Locating spreadsheet file.');
                $uuidPath = $this->getFilePathForFile($file);
                $filePath = Storage::disk('mcfs')->path("{$uuidPath}");
                $etlState->progress(12, 'Spreadsheet file located.');
            }

            $etlState->completeStep('read_spreadsheet', 'Spreadsheet source is ready.');
            $etlState->progress(15, 'Spreadsheet source is ready.');
            $importer = new EntityActivityImporter($this->projectId, $this->experimentId, $this->userId, $etlState);
            $importer->execute($filePath);
            $etlState->startStep('finalize', 'Finalizing import.');
            $etlState->progress(95, 'Finalizing import.');

            $experiment->update([
                'loading_finished_at' => Carbon::now(),
                'job_id'              => null,
            ]);

            $etlState->done();

            Mail::to(User::findOrFail($this->userId))
                ->send(new SpreadsheetLoadFinishedMail($file, $this->sheetUrl, Project::findOrFail($this->projectId),
                    $experiment,
                    $etlState->etlRun));
            if (!is_null($this->sheetUrl)) {
                // need to delete the temporary file.
                Storage::disk('mcfs')->delete('__sheets/'.$fileName);
            }
        } catch(Throwable $e) {
            if (!is_null($etlState)) {
                $etlState->failed($e->getMessage());
            }

            $experiment->update([
                'loading_finished_at' => Carbon::now(),
                'job_id'              => null,
            ]);

            throw $e;
        } finally {
            if (!blank($this->sheetUrl) && !blank($fileName)) {
                Storage::disk('mcfs')->delete('__sheets/'.$fileName);
            }
        }
    }

    private function downloadSheetAndReturnFileName(): string
    {
        $filename = uniqid().'.xlsx';
        @Storage::disk('mcfs')->makeDirectory('__sheets');
        @chmod(Storage::disk('mcfs')->path('__sheets'), 0777);
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
