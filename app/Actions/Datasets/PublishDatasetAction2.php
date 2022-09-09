<?php

namespace App\Actions\Datasets;

use App\Jobs\Datasets\SendPublishedDatasetUpdatedEmailJob;
use App\Mail\Datasets\PublishedDatasetReadyMail;
use App\Mail\Datasets\PublishedDatasetUpdatedMail;
use App\Models\Dataset;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

class PublishDatasetAction2
{
    public function execute(Dataset $dataset, User $user)
    {
        $now = Carbon::now();
        $dataset->update([
            'published_at'       => $now,
            'publish_started_at' => $now,
        ]);
        Bus::chain([
            function () use ($dataset) {
                ini_set("memory_limit", "4096M");
                $createDatasetFilesTableAction = new CreateDatasetFilesTableAction();
                $createDatasetFilesTableAction->execute($dataset);
            },
            function () use ($dataset) {
                ini_set("memory_limit", "4096M");
                $replicator = new ReplicateDatasetEntitiesAndRelationshipsForPublishingAction();
                $replicator->execute($dataset);
            },
            function () use ($dataset) {
                ini_set("memory_limit", "4096M");
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
            function () use ($dataset, $user) {
                $dataset->update(['publish_started_at' => null]);
                $mail = new PublishedDatasetReadyMail($dataset, $user);
                Mail::to($user)->send($mail);
            },
            function () use ($dataset) {
                $dataset->load(['notifications.owner']);
                $dataset->notifications->each(function (Notification $notification) use ($dataset) {
                    SendPublishedDatasetUpdatedEmailJob::dispatch($dataset, $notification->owner);
                });
            }
        ])->onQueue('globus')->dispatch();
    }
}