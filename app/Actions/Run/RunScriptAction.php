<?php

namespace App\Actions\Run;

use App\Actions\Projects\CreateProjectFilesAtLocationAction;
use App\Actions\Projects\ImportFilesIntoProjectAtLocationAction;
use App\Jobs\RunUserPythonScriptJob;
use App\Mail\Runs\ScriptRunCompletedMail;
use App\Models\File;
use App\Models\Project;
use App\Models\Script;
use App\Models\ScriptRun;
use App\Models\User;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Mail;
use function is_null;

class RunScriptAction
{
    private Script $script;
    private Project $project;

    private ?File $dir;
    private User $user;
    private ScriptRun $run;

    public function execute(File $file, Project $project, User $user, ?File $dir = null, ?File $inputFile = null,
                                 $synchronous = false)
    {
        $this->project = $project;
        $this->user = $user;
        $this->dir = $dir;
        $this->script = Script::createScriptForFileIfNeeded($file);

        $this->run = ScriptRun::create([
            'script_id'  => $this->script->id,
            'owner_id'   => $user->id,
            'project_id' => $project->id,
        ]);

        if ($synchronous) {
            $this->runSynchronously($inputFile);
            return $this->run;
        }

        Bus::chain([
            function () {
                $createProjectFilesAtLocationAction = new CreateProjectFilesAtLocationAction();
                $createProjectFilesAtLocationAction->execute($this->project,
                    "mcfs",
                    "__script_runs_in/{$this->run->uuid}");
            },
            new RunUserPythonScriptJob($this->run, $this->dir, $inputFile),
            function () {
                $action = new ImportFilesIntoProjectAtLocationAction();
                $action->execute($this->project, 'script_runs_out', $this->run->uuid, $this->user);
            },
            function () {
                try {
                    Storage::disk('mcfs')->deleteDirectory("__script_runs_in/{$this->run->uuid}");
                } catch (\Exception $e) {
                    Log::error("Unable to delete run script dir __script_runs_in/{$this->run->uuid}: {$e->getMessage()}");
                }
            },
            function () {
                $this->run->load('owner');
                Mail::to($this->run->owner)
                    ->queue(new ScriptRunCompletedMail($this->run));
            }
        ])->onQueue('scripts')->dispatch();

        return $this->run;
    }

    private function runSynchronously($inputFile): void
    {
        $createProjectFilesAtLocationAction = new CreateProjectFilesAtLocationAction();
        $createProjectFilesAtLocationAction->execute($this->project, "mcfs", "__script_runs_in/{$this->run->uuid}");

        RunUserPythonScriptJob::dispatchSync($this->run, $this->dir, $inputFile);

        $action = new ImportFilesIntoProjectAtLocationAction();
        $action->execute($this->project, 'script_runs_out', $this->run->uuid, $this->user);

        $this->run->load('owner');
        Mail::to($this->run->owner)
            ->queue(new ScriptRunCompletedMail($this->run));
    }
}