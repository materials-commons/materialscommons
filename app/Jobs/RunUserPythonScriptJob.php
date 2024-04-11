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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
        $inputPath = Storage::disk("mcfs")->path("__script_runs_in/{$this->run->uuid}");
        Storage::disk("scripts_out")->makeDirectory($this->run->uuid);
        $outputPath = Storage::disk("scripts_out")->path($this->run->uuid);
        $scriptDir = $this->run->script->scriptFile->directory->path;
        $dockerRunCommand = "docker run -d -it -e SCRIPT_DIR='/data/${scriptDir}' -v {$inputPath}:/data -v {$outputPath}:/out mc/mcpyimage";
        $dockerRunProcess = Process::fromShellCommandline($dockerRunCommand);
        $dockerRunProcess->start();
        $dockerRunProcess->wait();
        $this->containerId = Str::squish($dockerRunProcess->getOutput());

        $scriptName = $this->run->script->scriptFile->name;
        $dockerExecCommand = "docker exec -it {$this->containerId} python /data/{$scriptDir}/{$scriptName}";
        $dockerExecProcess = Process::fromShellCommandline($dockerExecCommand);
        $dockerExecProcess->start();
        $dockerExecProcess->wait();

        $dockerContainerStopCommand = "docker stop {$this->containerId}";
        $dockerContainerStopProcess = Process::fromShellCommandline($dockerContainerStopCommand);
        $dockerContainerStopProcess->start();
        $dockerContainerStopProcess->wait();

        $dockerContainerRmCommand = "docker container rm {$this->containerId}";
        $dockerContainerRmProcess = Process::fromShellCommandline($dockerContainerRmCommand);
        $dockerContainerRmProcess->start();
        $dockerContainerRmProcess->wait();
    }

    public function failed($exception)
    {
        $dockerContainerStopCommand = "docker stop {$this->containerId}";
        $dockerContainerStopProcess = Process::fromShellCommandline($dockerContainerStopCommand);
        $dockerContainerStopProcess->start();
        $dockerContainerStopProcess->wait();

        $dockerContainerRmCommand = "docker container rm {$this->containerId}";
        $dockerContainerRmProcess = Process::fromShellCommandline($dockerContainerRmCommand);
        $dockerContainerRmProcess->start();
        $dockerContainerRmProcess->wait();
    }
}
