<?php

namespace App\Console\Commands\Transfer;

use App\Actions\Globus\GlobusApi;
use App\Models\Conversion;
use App\Models\File;
use App\Models\TransferRequest;
use App\Models\TransferRequestFile;
use App\Traits\PathForFile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class RemoveClosedTransferRequestsCommand extends Command
{
    use PathForFile;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc-transfer:remove-closed-transfer-requests';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete closed transfer requests';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $globusApi = GlobusApi::createGlobusApi();
        TransferRequest::with(['globusTransfer', 'transferRequestFiles.file'])->where('state', 'closed')
                       ->get()
                       ->each(function (TransferRequest $transferRequest) use ($globusApi) {
                           if (isset($transferRequest->globusTransfer)) {
                               try {
                                   $globusApi->deleteEndpointAclRule($transferRequest->globusTransfer->globus_endpoint_id,
                                       $transferRequest->globusTransfer->globus_acl_id);
                               } catch (\Exception $e) {
                                   Log::error("Unable to delete acl");
                               }
                           }
                           if (!isset($transferRequest->transferRequestFiles)) {
                               return;
                           }
                           $transferRequest->transferRequestFiles->each(function (TransferRequestFile $trFile) {
                               $isUsed = File::where('checksum', $trFile->file->checksum)
                                             ->where('uses_uuid', $trFile->file->uuid)
                                             ->whereNull('deleted_at')
                                             ->first();
                               if (!is_null($isUsed)) {
                                   // This file is referenced by another file, so the only thing we need
                                   // to do is see if it should be converted, and then return.
                                   if ($trFile->file->shouldBeConverted()) {
                                       Conversion::create([
                                           'file_id'    => $trFile->file->id,
                                           'project_id' => $trFile->file->project_id,
                                           'owner_id'   => $trFile->file->owner_id,
                                       ]);
                                   }
                                   return;
                               }

                               // If we are here, then the file isn't being used, so we need to determine
                               // if its checksum matches any other files, and if it does, point at that
                               // file and delete the download of this file.
                               $existing = File::where('checksum', $trFile->file->checksum)
                                               ->where('id', '<>', $trFile->file->id)
                                   ->whereNull('dataset_id')
                                               ->whereNull('deleted_at')
                                               ->first();
                               if (!is_null($existing)) {
                                   $usesUuid = $existing->getFileUuidToUse();
                                   $usesId = $existing->getFileUsesIdToUse();
                                   $trFile->file->update([
                                       'uses_uuid' => $usesUuid,
                                       'uses_id'   => $usesId,
                                   ]);
                                   Storage::disk('mcfs')->delete($this->getFilePathPartialFromUuid($trFile->file->uuid));
                               }
                               if ($trFile->file->shouldBeConverted()) {
                                   Conversion::create([
                                       'file_id'    => $trFile->file->id,
                                       'project_id' => $trFile->file->project_id,
                                       'owner_id'   => $trFile->file->owner_id,
                                   ]);
                               }
                           });
                           $transferRequest->delete();
                       });
        return 0;
    }

}
