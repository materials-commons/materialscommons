<?php

namespace App\Http\Controllers\Web\Globus\Drop;

use App\Actions\Globus\GlobusApi;
use App\Http\Controllers\Controller;
use App\Mail\Globus\DropReadyForImportMail;
use App\Models\GlobusDrop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MarkDropTransferDoneWebController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, $uuid)
    {
        $drop = GlobusDrop::where('uuid', $uuid)->first();
        if (is_null($drop)) {
            return redirect("/");
        }

        // Update the drop to say it's ready for import
        $drop->update(['status' => 'ready for import']);

        // Delete ACLs on path so that nothing else can access the drop.
        $globusApi = GlobusApi::createGlobusApi();
        $globusApi->deleteACLsOnPath($drop->globus_endpoint_id, $drop->globus_path);

        // Send email to drop owner that the drop is available for import
        $dropEmail = new DropReadyForImportMail($drop);
        Mail::to($drop->owner->email)->queue($dropEmail->onQueue('globus'));

        return redirect("/");
    }
}
