<?php

namespace App\Jobs;

use App\Models\File;
use App\Models\Project;
use App\Models\Script;
use App\Models\ScriptRun;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\Process\Process;

class RunUserPythonScriptJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;

    public ScriptRun $run;
    private $containerId;

    /**
     * Create a new job instance.
     */
    public function __construct(ScriptRun $run)
    {
        $this->run = $run;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->run->load(['project', 'owner', 'script.scriptFile.directory']);
        $dockerRunCommand = "docker run -d -it -v /some/path:/data -v /some/output/path:/out mc/mcpyimage";
        $dockerRunProcess = Process::fromShellCommandline($dockerRunCommand);
        $dockerRunProcess->start(null, []);
        $dockerRunProcess->wait();
        $this->containerId = $dockerRunProcess->getOutput();

        $scriptDir = $this->run->script->scriptFile->directory->path;
        $scriptName = $this->run->script->scriptFile->name;
        $dockerExecCommand = "docker exec -it {$this->run->script->container} /data/{$scriptDir}/{$scriptName}";
        $dockerExecProcess = Process::fromShellCommandline($dockerExecCommand);
        $dockerExecProcess->start(null, [
            "SCRIPT_DIR" => "/data/{$scriptDir}",
        ]);
        $dockerExecProcess->wait();

        $dockerContainerRmCommand = "docker container rm {$this->containerId}";
        $dockerContainerRmProcess = Process::fromShellCommandline($dockerContainerRmCommand);
        $dockerContainerRmProcess->start(null, []);
        $dockerContainerRmProcess->wait();
    }

    public function failed($exception)
    {
        $dockerContainerStopCommand = "docker stop {$this->containerId}";
        $dockerContainerStopProcess = Process::fromShellCommandline($dockerContainerStopCommand);
        $dockerContainerStopProcess->start(null, []);
        $dockerContainerStopProcess->wait();

        $dockerContainerRmCommand = "docker container rm {$this->containerId}";
        $dockerContainerRmProcess = Process::fromShellCommandline($dockerContainerRmCommand);
        $dockerContainerRmProcess->start(null, []);
        $dockerContainerRmProcess->wait();
    }
}
