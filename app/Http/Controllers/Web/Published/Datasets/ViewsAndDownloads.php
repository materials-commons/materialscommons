<?php

namespace App\Http\Controllers\Web\Published\Datasets;

use App\Helpers\ClientIPAddress;
use App\Models\Dataset;
use App\Models\Download;
use App\Models\View;
use App\Models\VirtualMachine;
use Illuminate\Support\Facades\DB;

trait ViewsAndDownloads
{
    public function incrementDatasetDownloads($datasetId)
    {
        $this->incrementDownloads($datasetId, Dataset::class);
    }

    public function incrementVirtualMachineDownloads($vmId)
    {
        $this->incrementDownloads($vmId, VirtualMachine::class);
    }

    public function incrementDownloads($id, $type)
    {
        $user = auth()->user();
        $who = $user != null ? $user->email : ClientIPAddress::getClientIp();
        DB::transaction(function () use ($who, $id, $type) {
            try {
                Download::create([
                    'downloadable_type' => $type,
                    'downloadable_id'   => $id,
                    'who'               => $who,
                ]);
            } catch (\Exception $e) {
                return;
            }
        });
    }

    public function incrementDatasetViews($datasetId)
    {
        $this->incrementViews($datasetId, Dataset::class);
    }

    public function incrementViews($id, $type)
    {
        $user = auth()->user();
        $who = $user != null ? $user->email : ClientIPAddress::getClientIp();
        DB::transaction(function () use ($who, $id, $type) {
            try {
                View::create([
                    'viewable_type' => $type,
                    'viewable_id'   => $id,
                    'who'           => $who,
                ]);
            } catch (\Exception $e) {
                return;
            }
        });
    }
}