<?php

namespace App\Jobs;

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

    public $script;

    public $project;

    public $run;

    private $containerId;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $dockerRunCommand = "docker run -d -it -v /some/path:/data -v /some/output/path:/out mc/mcpyimage";
        $dockerRunProcess = Process::fromShellCommandline($dockerRunCommand);
        $dockerRunProcess->start(null, [
            "environment variables" => "here",
        ]);
        $dockerRunProcess->wait();
        $this->containerId = $dockerRunProcess->getOutput();

        $dockerExecCommand = "docker exec -it {$this->containerId} {$this->script}";
        $dockerExecProcess = Process::fromShellCommandline($dockerExecCommand);
        $dockerExecProcess->start(null, [
            "environment variables" => "here",
        ]);
        $dockerExecProcess->wait();

        $dockerContainerRmCommand = "docker container rm {$this->containerId}";
        $dockerContainerRmProcess = Process::fromShellCommandline($dockerContainerRmCommand);
        $dockerContainerRmProcess->start(null, [
            "environment variables" => "here",
        ]);
        $dockerContainerRmProcess->wait();
    }

    public function failed($exception)
    {
        $dockerContainerStopCommand = "docker stop {$this->containerId}";
        $dockerContainerStopProcess = Process::fromShellCommandline($dockerContainerStopCommand);
        $dockerContainerStopProcess->start(null, [
            "environment variables" => "here",
        ]);
        $dockerContainerStopProcess->wait();

        $dockerContainerRmCommand = "docker container rm {$this->containerId}";
        $dockerContainerRmProcess = Process::fromShellCommandline($dockerContainerRmCommand);
        $dockerContainerRmProcess->start(null, [
            "environment variables" => "here",
        ]);
        $dockerContainerRmProcess->wait();
    }
}
