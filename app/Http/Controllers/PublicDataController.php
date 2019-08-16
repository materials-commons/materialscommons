<?php

namespace App\Http\Controllers;

use App\Models\Dataset;
use Freshbitsweb\Laratables\Laratables;
use Illuminate\Http\Request;

class PublicDataController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('public.index');
    }

    /**
     * Return all published datasets for datatables
     * @return array
     */
    public function getAllPublishedDatasets()
    {
        return Laratables::recordsOf(Dataset::class, function ($query) {
            return $query->whereNotNull('published_at');
        });
    }
}
