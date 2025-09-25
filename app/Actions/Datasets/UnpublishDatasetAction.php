<?php

namespace App\Actions\Datasets;

use App\Jobs\Datasets\Unpublish\DeleteDatasetGlobusAndZipfilesJob;
use App\Jobs\Datasets\Unpublish\DeleteDatasetRelationshipsJob;
use App\Jobs\Datasets\Unpublish\DeletePublishedFilesJob;
use App\Mail\Datasets\UnpublishDatasetCompleteMail;
use App\Models\Dataset;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Mail;

class UnpublishDatasetAction
{
    public function __invoke(Dataset $dataset, User $user, $republish = false)
    {
        $dataset->update([
            'published_at'       => null,
            'test_published_at'  => null,
            'zipfile_size'       => 0,
            'globus_path_exists' => false,
            'cleanup_started_at' => Carbon::now(),
        ]);
        Bus::chain([
            new DeletePublishedFilesJob($dataset),
            new DeleteDatasetRelationshipsJob($dataset),
            new DeleteDatasetGlobusAndZipfilesJob($dataset),
            function () use ($dataset, $user) {
                $dataset->update(['cleanup_started_at' => null]);
                $mail = new UnpublishDatasetCompleteMail($dataset, $user);
                Mail::to($user)->send($mail);
            },
            function () use ($dataset, $user, $republish) {
                if ($republish) {
                    $publishAction = new PublishDatasetAction2();
                    $publishAction->execute($dataset, $user);
                }
            }
        ])->onQueue('globus')->dispatch();

        return $dataset->fresh();
    }
}
