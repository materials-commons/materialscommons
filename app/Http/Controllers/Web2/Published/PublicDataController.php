<?php

namespace App\Http\Controllers\Web2\Published;

use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\Dataset;
use Freshbitsweb\Laratables\Laratables;
use function config;

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
                         ->where('doi', 'not like', '%'.config('doi.test_namespace').'%')
                         ->orWhereNull('doi')
                         ->whereDoesntHave('tags', function ($q) {
                             $q->where('tags.id', config('visus.import_tag_id'));
                         })
                         ->whereNotNull('published_at');
        });
    }

    public function getAllPublishedTestDatasets()
    {
        return Laratables::recordsOf(Dataset::class, function ($query) {
            return $query->withCount('views', 'downloads')
                         ->whereLike('doi', '%'.config('doi.test_namespace').'%')
                         ->whereDoesntHave('tags', function ($q) {
                             $q->where('tags.id', config('visus.import_tag_id'));
                         })
                         ->whereNotNull('published_at');
        });
    }
}
