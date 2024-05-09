<?php

namespace App\Http\Controllers\Web\WebDAV;

use App\Http\Controllers\Controller;
use App\Services\MCDavApiService;
use Illuminate\Http\Request;

class ResetWebDAVStateForUserWebController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = auth()->user();
        if (MCDavApiService::ResetUserState($user)) {
            flash("WebDAV state reset. You may start creating new file content.")->success();
        } else {
            flash("Failed reseting WebDAV state.")->error();
        }

        return redirect(route('dashboard'));
    }
}
