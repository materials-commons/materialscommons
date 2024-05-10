<?php

namespace App\Http\Controllers\Web\Admin\MCFS;

use App\Http\Controllers\Controller;
use App\Traits\SearchFile;
use Illuminate\Http\Request;

class SearchMCFSLogWebController extends Controller
{
    use SearchFile;

    public function __invoke(Request $request)
    {
        $search = $request->get("search");
        return $this->search('mc_logs', "mcfsd.log", $search);
    }
}
