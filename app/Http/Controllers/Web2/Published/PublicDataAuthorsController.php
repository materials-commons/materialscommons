<?php

namespace App\Http\Controllers\Web2\Published;

use App\Http\Controllers\Controller;

class PublicDataAuthorsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {
        return view('public.authors.index');
    }
}
