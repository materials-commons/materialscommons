<?php

namespace App\Http\Controllers\Web2\Published;

use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\Dataset;
use Freshbitsweb\Laratables\Laratables;

class PublicDataController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $communities = Community::with('owner')->withCount('datasets')
                                ->where('public', true)
                                ->orderBy('name')
                                ->get();
        return view('public.index', compact('communities'));
    }

    /**
     * Return all published datasets for datatables
     * @return array
     */
    public function getAllPublishedDatasets()
    {
        return Laratables::recordsOf(Dataset::class, function ($query) {
            return $query->withCount('views', 'downloads')
                         ->whereNotNull('published_at')
                         ->orderBy('published_at', 'desc');
        });
    }
}
