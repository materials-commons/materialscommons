<?php

namespace App\Actions\Run;

use App\Actions\Projects\CreateProjectFilesAtLocationAction;
use App\Jobs\RunUserPythonScriptJob;
use App\Models\File;
use App\Models\Project;
use App\Models\Script;
use App\Models\ScriptRun;
use App\Models\User;
use Illuminate\Support\Facades\Bus;
use function is_null;

class RunScriptAction
{
    private Script $script;
    private Project $project;
    private ScriptRun $run;

    public function execute(File $file, Project $project, User $user)
    {
        $this->project = $project;
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

        Bus::chain([
            function () {
                $createProjectFilesAtLocationAction = new CreateProjectFilesAtLocationAction();
                $createProjectFilesAtLocationAction->execute($this->project,
                    "mcfs",
                    "__script_runs/{$this->run->uuid}");
            },
            new RunUserPythonScriptJob($this->run),
        ]);


    }
}