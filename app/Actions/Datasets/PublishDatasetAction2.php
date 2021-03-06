<?php

namespace App\Actions\Datasets;

use App\Models\Dataset;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

class PublishDatasetAction2
{
    public function execute(Dataset $dataset)
    {
        $dataset->update(['published_at' => Carbon::now()]);
        Bus::chain([
            function () use ($dataset) {
                $createDatasetFilesTableAction = new CreateDatasetFilesTableAction();
                $createDatasetFilesTableAction->execute($dataset);
            },
            function () use ($dataset) {
                $replicator = new ReplicateDatasetEntitiesAndRelationshipsForPublishingAction();
                $replicator->execute($dataset);
            },
            function () use ($dataset) {
                $createDatasetInGlobusAction = new CreateDatasetInGlobusAction();
                $createDatasetInGlobusAction($dataset);
            },
            function () use ($dataset) {
                Storage::disk('mcfs')->makeDirectory("zip_logs");
                $logPath = Storage::disk('mcfs')->path("zip_logs/{$dataset->uuid}.log");
                $command = "nohup /usr/local/bin/mcdszip -d {$dataset->id} -z {$dataset->zipfilePath()} > {$logPath} 2>&1&";
                $process = Process::fromShellCommandline($command);
                $process->start(null, [
                    'MCFS_DIR'    => config('filesystems.disks.mcfs.root'),
                    'DB_USERNAME' => config('database.connections.mysql.username'),
                    'DB_PASSWORD' => config('database.connections.mysql.password'),
                    'DB_HOST'     => config('database.connections.mysql.host'),
                    'DB_PORT'     => config('database.connections.mysql.port'),
                    'DB_DATABASE' => config('database.connections.mysql.database'),
                ]);
            },
        ])->onQueue('globus')->dispatch();
    }
}