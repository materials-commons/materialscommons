<?php

namespace App\Http\Controllers\Web\Admin\MCFS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShowMCFSLogViewerWebController extends Controller
{
    public function __invoke(Request $request)
    {
        return view('app.admin.mcfs.show-log-viewer');
    }
}
