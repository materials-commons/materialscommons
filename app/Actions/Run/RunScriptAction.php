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
use Mail;
use function is_null;

class RunScriptAction
{
    private Script $script;
    private Project $project;

    private User $user;
    private ScriptRun $run;

    public function execute(File $file, Project $project, User $user, $synchronous = false)
    {
        $this->project = $project;
        $this->user = $user;
        $s = Script::where('script_file_id', $file->id)->first();
        if (is_null($s)) {
            // Create a new script
            $s = Script::create([
                'description'    => 'Create script run',
                'queue'          => 'globus',
                'container'      => 'mc/mcpyimage',
                'script_file_id' => $file->id,
            ]);
        }

        $this->script = $s;

        $this->run = ScriptRun::create([
            'script_id'  => $this->script->id,
            'owner_id'   => $user->id,
            'project_id' => $project->id,
        ]);

        if ($synchronous) {
            $this->runSynchronously();
            return $this->run;
        }

        Bus::chain([
            function () {
                echo "running CreateProjectFilesAtLocatonAction\n";
                $createProjectFilesAtLocationAction = new CreateProjectFilesAtLocationAction();
                $createProjectFilesAtLocationAction->execute($this->project,
                    "mcfs",
                    "__script_runs_in/{$this->run->uuid}");
            },
            new RunUserPythonScriptJob($this->run),
            function () {
                echo "Running ImportFilesIntoProjectAtLocationAction\n";
                $action = new ImportFilesIntoProjectAtLocationAction();
                $action->execute($this->project, 'script_runs_out', $this->run->uuid, $this->user);
            },
            function () {
                $this->run->load('owner');
                Mail::to($this->run->owner)
                    ->queue(new ScriptRunCompletedMail($this->run));
            }
        ])->onQueue('globus')->dispatch();

        return $this->run;
    }

    private function runSynchronously()
    {
        echo "running CreateProjectFilesAtLocatonAction\n";
        $createProjectFilesAtLocationAction = new CreateProjectFilesAtLocationAction();
        $createProjectFilesAtLocationAction->execute($this->project, "mcfs", "__script_runs_in/{$this->run->uuid}");

        RunUserPythonScriptJob::dispatchSync($this->run);

//        echo "Running ImportFilesIntoProjectAtLocationAction\n";
//        $action = new ImportFilesIntoProjectAtLocationAction();
//        $action->execute($this->project, 'script_runs_out', $this->run->uuid, $this->user);

        //$this->run->load('owner');
//        Mail::to($this->run->owner)
//            ->queue(new ScriptRunCompletedMail($this->run));
    }
}