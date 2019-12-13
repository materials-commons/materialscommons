<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Http\Controllers\Controller;

class ShowDashboardWebController extends Controller
{
    public function __invoke()
    {
        $user = auth()->user();
        $globusUploads = $user->globusUploads;

        return view('app.dashboard.index', compact('globusUploads', 'user'));
    }
}
