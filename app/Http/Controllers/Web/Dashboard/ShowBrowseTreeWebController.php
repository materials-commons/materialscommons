<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShowBrowseTreeWebController extends Controller
{
    public function __invoke(Request $request)
    {
        return view('app.dashboard.browse-tree.show');
    }
}
