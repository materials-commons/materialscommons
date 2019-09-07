<?php

namespace App\Http\Controllers\Web2\Published;

use App\Http\Controllers\Controller;

class PublicDataTagsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {
        return view('public.tags.index');
    }
}
