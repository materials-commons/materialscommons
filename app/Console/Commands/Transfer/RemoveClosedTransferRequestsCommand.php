<?php

namespace App\Console\Commands\Transfer;

use App\Actions\Globus\GlobusApi;
use App\Models\Conversion;
use App\Models\File;
use App\Models\TransferRequest;
use App\Models\TransferRequestFile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class RemoveClosedTransferRequestsCommand extends Command
{
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
                           if (!isset($transferRequest->globusTransfer)) {
                               try {
                                   $globusApi->deleteEndpointAclRule($transferRequest->globusTransfer->globus_endpoint_id,
                                       $transferRequest->globusTransfer->globus_acl_id);
                               } catch (\Exception $e) {
                                   Log::error("Unable to delete acl");
                               }
                           }
                           $transferRequest->transferRequestFiles->each(function (TransferRequestFile $trFile) {
                               $existing = File::where('checksum', $trFile->file->checksum)
                                               ->where('id', '<>', $trFile->file->id)
                                               ->first();
                               if (!is_null($existing)) {
                                   $usesUuid = $existing->uuid;
                                   $usesId = $existing->id;
                                   if (!is_null($existing->uses_uuid)) {
                                       $usesUuid = $existing->uses_uuid;
                                       $usesId = $existing->uses_id;
                                   }

                                   $trFile->file->update([
                                       'uses_uuid' => $usesUuid,
                                       'uses_id'   => $usesId,
                                   ]);
                                   Storage::disk('mcfs')->delete($this->getFilePathPartialFromUid($trFile->file->uuid));
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
