<?php

namespace App\Http\Controllers\Web2\Published;

use App\Http\Controllers\Controller;

class PublicDataProjectsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {
        return view('public.projects.index');
    }
}
