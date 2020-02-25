<?php

namespace App\Http\Controllers\Web\Published\Datasets;

use App\Helpers\ClientIPAddress;
use App\Models\Dataset;
use App\Models\Download;
use App\Models\View;
use Illuminate\Support\Facades\DB;

trait ViewsAndDownloads
{
    public function incrementDownloads($datasetId)
    {
        $user = auth()->user();
        $who = $user != null ? $user->email : ClientIPAddress::getClientIp();
        DB::transaction(function () use ($who, $datasetId) {
            try {
                Download::create([
                    'downloadable_type' => Dataset::class,
                    'downloadable_id'   => $datasetId,
                    'who'               => $who,
                ]);
            } catch (\Exception $e) {
                return;
            }
        });
    }

    public function incrementViews($datasetId)
    {
        $user = auth()->user();
        $who = $user != null ? $user->email : ClientIPAddress::getClientIp();
        DB::transaction(function () use ($who, $datasetId) {
            try {
                View::create([
                    'viewable_type' => Dataset::class,
                    'viewable_id'   => $datasetId,
                    'who'           => $who,
                ]);
            } catch (\Exception $e) {
                return;
            }
        });
    }
}

