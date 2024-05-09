<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShowAdminDashboardWebController extends Controller
{
    public function __invoke(Request $request)
    {
        abort_unless(auth()->user()->is_admin, 404, "Not allowed");
        return redirect()->route('admin.dashboard.mcfs.index');
    }
}
