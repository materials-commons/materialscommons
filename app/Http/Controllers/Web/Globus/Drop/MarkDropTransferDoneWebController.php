<?php

namespace App\Http\Controllers\Web\Globus\Drop;

use App\Actions\Globus\EndpointAclRule;
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
            return view('app.projects.globus.drops.not-found');
        }

        // Update the drop to say it's ready for import
        $drop->update(['status' => 'ready for import']);

        // Delete ACLs on the path so that nothing else can access the drop. We delete all ACLs
        // and then add back the drop owner so they can view the files on the drop. Since the
        // individual ACL ids aren't being tracked, it is easier to delete all the ACLs on the
        // path and then selectively add back the drop owner.
        $globusApi = GlobusApi::createGlobusApi();
        $globusApi->deleteACLsOnPath($drop->globus_endpoint_id, $drop->globus_path);

        // Now add back the drop owner so they can view the files on the drop.
        $this->setAclOnPathForUser($globusApi, $drop->globus_path, $drop->owner->email, $drop->globus_endpoint_id);

        // Send email to drop owner that the drop is available for import
        $dropEmail = new DropReadyForImportMail($drop);
        Mail::to($drop->owner->email)->queue($dropEmail->onQueue('globus'));

        return view('app.projects.globus.drops.inform-done', [
            'drop' => $drop,
        ]);
    }

    private function getGlobusIdentity($globusApi, $globusEmail)
    {
        $resp = $globusApi->getIdentities([$globusEmail]);
        if (
            !isset($resp['identities']) ||
            !is_array($resp['identities']) ||
            !isset($resp['identities'][0]) ||
            !isset($resp['identities'][0]['id'])
        ) {
            return null;
        }

        return $resp["identities"][0]["id"];
    }

    private function setAclOnPathForUser($globusApi, $globusPath, $email, $endpointId)
    {
        $globusUserId = $this->getGlobusIdentity($globusApi, $email);
        if (!is_null($globusUserId)) {
            $this->setAclOnPath($globusApi, $globusPath, $globusUserId, $endpointId);
        }

        $globusUserId = $this->getGlobusIdentity($globusApi, $email."@accounts.google.com");
        if (!is_null($globusUserId)) {
            $this->setAclOnPath($globusApi, $globusPath, $globusUserId, $endpointId);
        }
    }

    private function setAclOnPath($globusApi, $globusPath, $globusUserId, $endpointId)
    {
        $endpointAclRule = new EndpointAclRule($globusUserId, $globusPath, "rw", $endpointId);
        $resp = $globusApi->addEndpointAclRule($endpointAclRule);
        if (!isset($resp['access_id'])) {
            return null;
        }
        return $resp["access_id"];
    }
}
