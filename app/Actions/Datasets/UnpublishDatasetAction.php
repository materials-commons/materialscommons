<?php

namespace App\Actions\Datasets;

use App\Jobs\Datasets\Unpublish\DeleteDatasetGlobusAndZipfilesJob;
use App\Jobs\Datasets\Unpublish\DeleteDatasetRelationshipsJob;
use App\Jobs\Datasets\Unpublish\DeletePublishedFilesJob;
use App\Models\Dataset;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Bus;

class UnpublishDatasetAction
{
    public function __invoke(Dataset $dataset, User $user)
    {
        $dataset->update([
            'published_at'       => null,
            'zipfile_size'       => 0,
            'globus_path_exists' => false,
            'cleanup_started_at' => Carbon::now(),
        ]);
        Bus::chain([
            new DeletePublishedFilesJob($dataset),
            new DeleteDatasetGlobusAndZipfilesJob($dataset),
            new DeleteDatasetRelationshipsJob($dataset),
            function () use ($dataset, $user) {
                $dataset->update(['cleanup_started_at' => null]);
//                $mail = new UnpublishDatasetCompleteMail($dataset, $user);
//                Mail::to($user)->send($mail);
            },
        ])->onQueue('globus')->dispatch();

        return $dataset->fresh();
    }
}