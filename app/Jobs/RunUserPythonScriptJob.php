<?php

namespace App\Jobs;

use App\Helpers\PathHelpers;
use App\Models\File;
use App\Models\ScriptRun;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;
use function get_current_user;

class RunUserPythonScriptJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;

    public ScriptRun $run;
    public ?File $dir;
    private string $containerId;

    /**
     * Create a new job instance.
     */
    public function __construct(ScriptRun $run, ?File $dir)
    {
        $this->run = $run;
        $this->dir = $dir;
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

        // Setup logging dir
        Storage::disk('mcfs')->makeDirectory("__run_script_logs");
        $logPathPartial = "__run_script_logs/{$this->run->uuid}.log";
        $logPath = Storage::disk('mcfs')->path($logPathPartial);

        // Run container
        $scriptDir = PathHelpers::normalizePath("/data/{$this->run->script->scriptFile->directory->path}");
        $user = posix_getuid();
        $contextDir = $this->getContextDir();
        $dockerRunCommand = "docker run -d --user {$user}:{$user} -it -e CONTEXT_DIR='${contextDir}' -v {$inputPath}:/data:ro -v {$outputPath}:/out mc/mcpyimage";
        Storage::disk('mcfs')->put($logPathPartial, "${dockerRunCommand}\n");
        $dockerRunProcess = Process::fromShellCommandline($dockerRunCommand);
        $dockerRunProcess->start();
        $dockerRunProcess->wait();
        $this->containerId = Str::squish($dockerRunProcess->getOutput());

        $this->run->update([
            'docker_container_id' => $this->containerId,
            'started_at'          => Carbon::now(),
        ]);

        // Run python script
        $scriptName = $this->run->script->scriptFile->name;
        $scriptPath = PathHelpers::normalizePath("{$scriptDir}/{$scriptName}");
        $dockerExecCommand = "docker exec --user {$user}:{$user} -t {$this->containerId} python ${scriptPath} >> {$logPath} 2>&1";
        Storage::disk('mcfs')->append($logPathPartial, "{$dockerExecCommand}\n");
        $dockerExecProcess = Process::fromShellCommandline($dockerExecCommand);
        $dockerExecProcess->start();
        $dockerExecProcess->wait();

        $this->run->update(['finished_at' => Carbon::now()]);

        // Stop container
        $dockerContainerStopCommand = "docker stop {$this->containerId}";
        $dockerContainerStopProcess = Process::fromShellCommandline($dockerContainerStopCommand);
        $dockerContainerStopProcess->start();
        $dockerContainerStopProcess->wait();

        // Delete container
        $dockerContainerRmCommand = "docker container rm {$this->containerId}";
        $dockerContainerRmProcess = Process::fromShellCommandline($dockerContainerRmCommand);
        $dockerContainerRmProcess->start();
        $dockerContainerRmProcess->wait();
    }

    private function getContextDir()
    {
        if (is_null($this->dir)) {
            return "/out";
        }

        return PathHelpers::normalizePath("/out/{$this->dir->path}");
    }

    public function failed($exception)
    {
        $this->run->update(['failed_at' => Carbon::now()]);

        // Stop container
        $dockerContainerStopCommand = "docker stop {$this->containerId}";
        $dockerContainerStopProcess = Process::fromShellCommandline($dockerContainerStopCommand);
        $dockerContainerStopProcess->start();
        $dockerContainerStopProcess->wait();

        // Delete container
        $dockerContainerRmCommand = "docker container rm {$this->containerId}";
        $dockerContainerRmProcess = Process::fromShellCommandline($dockerContainerRmCommand);
        $dockerContainerRmProcess->start();
        $dockerContainerRmProcess->wait();
    }
}
