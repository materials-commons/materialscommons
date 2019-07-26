<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublicDataProjectsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {
        return view('public.projects.index');
    }
}
