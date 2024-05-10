<?php

namespace App\Http\Controllers\Web\Admin\MCFS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ShowMCFSLogWebController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $content = Storage::disk('mc_logs')->get("mcfsd.log");
        return "<pre style='white-space: pre-wrap'>{$content}</pre>";
    }
}
