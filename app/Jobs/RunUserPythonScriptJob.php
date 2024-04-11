<?php

namespace App\Jobs;

use App\Helpers\PathHelpers;
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
use Illuminate\Support\Carbon;
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
        Storage::disk("script_runs_out")->makeDirectory($this->run->uuid);
        $outputPath = Storage::disk("script_runs_out")->path($this->run->uuid);
        $scriptDir = PathHelpers::normalizePath("/data/{$this->run->script->scriptFile->directory->path}");
        $dockerRunCommand = "docker run -d -it -e SCRIPT_DIR='${scriptDir}' -v {$inputPath}:/data:ro -v {$outputPath}:/out mc/mcpyimage";
        echo "dockerRunCommand = {$dockerRunCommand}\n";
        $dockerRunProcess = Process::fromShellCommandline($dockerRunCommand);
        $dockerRunProcess->start();
        $dockerRunProcess->wait();
        $this->containerId = Str::squish($dockerRunProcess->getOutput());

        $this->run->update([
            'docker_container_id' => $this->containerId,
            'started_at'          => Carbon::now(),
        ]);

        $scriptName = $this->run->script->scriptFile->name;
        $scriptPath = PathHelpers::normalizePath("{$scriptDir}/{$scriptName}");
        $dockerExecCommand = "docker exec -t {$this->containerId} python ${scriptPath} > /tmp/script.out 2>&1";
        echo "dockerExecCommand = {$dockerExecCommand}\n";
        $dockerExecProcess = Process::fromShellCommandline($dockerExecCommand);
        $dockerExecProcess->start();
        $dockerExecProcess->wait();

        $this->run->update(['finished_at' => Carbon::now()]);

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
        $this->run->update(['failed_at' => Carbon::now()]);

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
