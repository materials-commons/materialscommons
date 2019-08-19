<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use Illuminate\Http\Request;

class PublicDataDatasetsController extends Controller
{
    /**
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {
        return view('public.datasets.index');
    }

    /**
     * Show dataset details
     *
     * @param  Dataset  $dataset
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Dataset $dataset) {
        return view('public.datasets.show', compact('dataset'));
    }
}
