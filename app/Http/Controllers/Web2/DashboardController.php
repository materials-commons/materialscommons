<?php

namespace App\Http\Controllers\Web2;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('app.dashboard.index');
    }
}
