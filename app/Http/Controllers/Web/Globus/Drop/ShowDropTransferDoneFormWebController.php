<?php

namespace App\Http\Controllers\Web\Globus\Drop;

use App\Http\Controllers\Controller;
use App\Models\GlobusDrop;
use Illuminate\Http\Request;

class ShowDropTransferDoneFormWebController extends Controller
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

        if ($drop->status !== "available") {
            return view('app.projects.globus.drops.not-found');
        }

        return view('app.projects.globus.drops.transfer-done-form', [
            'drop' => $drop,
        ]);
    }
}
