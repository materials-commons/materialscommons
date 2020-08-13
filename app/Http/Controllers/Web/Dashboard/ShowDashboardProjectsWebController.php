<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShowDashboardProjectsWebController extends Controller
{
    public function __invoke(Request $request)
    {
        return view('app.dashboard.index', [
            'projects' => auth()->user()->projects()->with(['owner'])->get(),
        ]);
    }
}
