<?php

namespace App\Actions\Datasets;

use App\Jobs\Datasets\SendPublishedDatasetUpdatedEmailJob;
use App\Mail\Datasets\PublishedDatasetReadyMail;
use App\Models\Dataset;
use App\Models\Notification;
use App\Models\User;
use App\Traits\Datasets\DatasetInfo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

class PublishDatasetAction2
{
    use DatasetInfo;

    // $publishAs can be 'public', 'private', or 'test'. This used to be a boolean field,
    // but it was not used anywhere in the code. Now that we support publishing as test
    // datasets, we need to be able to set the publish_as field to 'test' when publishing.
    // The old field was renamed, and now we can support 3 different ways of publishing a
    // dataset. These ways control the visibility. For the moment only 'public' and 'test'
    // are supported in the app.
    public function execute(Dataset $dataset, User $user, $publishAs = 'public')
    {

        $dataset->update([
            'publish_started_at' => Carbon::now(),
        ]);
        Bus::chain([
            function () use ($dataset, &$datasetFileSize) {
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
            function () use ($dataset, $user, $publishAs) {
                $publishedAtField = $this->getPublishedAtField($publishAs);
                $dataset->update([
                    'publish_started_at' => null,
                    $publishedAtField    => Carbon::now(),
                ]);
                $mail = new PublishedDatasetReadyMail($dataset, $user);
                Mail::to($user)->send($mail);
            },
            function () use ($dataset) {
                $dataset->load(['notifications.owner']);
                $dataset->notifications->each(function (Notification $notification) use ($dataset) {
                    SendPublishedDatasetUpdatedEmailJob::dispatch($dataset, $notification->owner);
                });
            },
        ])->onQueue('globus')->dispatch();
    }

    // getPublishedAtField determines which field in the Datasets model to set. To preserve
    // backwards compatibility, we default to 'published_at' if the publishAs parameter doesn't
    // match 'private' or 'test'.
    private function getPublishedAtField($publishAs): string
    {
        switch ($publishAs) {
            case 'private':
                return 'privately_published_at';
            case 'test':
                return 'test_published_at';
            default:
                return 'published_at';
        }
    }
}
