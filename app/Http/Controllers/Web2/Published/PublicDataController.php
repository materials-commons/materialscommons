<?php

namespace App\Http\Controllers\Web2\Published;

use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\Dataset;
use Freshbitsweb\Laratables\Laratables;
use Illuminate\Support\Facades\DB;
use function config;

class PublicDataController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        // Check if the ?test query parameter is set. This means we only want to show
        // datasets that have been published for testing purposes.
        $isTest = request()->has('test');
        $dateField = $isTest ? 'test_published_at' : 'published_at';

        $communities = Community::with('owner')->withCount('datasets')
                                ->where('public', true)
                                ->orderBy('name')
                                ->where('name', '<>', 'OpenVisus')
                                ->get();

        $datasetCount = Dataset::whereNotNull($dateField)->count();

        // Count distinct tags that appear on published datasets (excluding filtered tags)
        $tagCount = DB::table('tags')
                      ->whereExists(fn($q) => $q->select(DB::raw(1))
                                                ->from('taggables')
                                                ->join('datasets', fn($j) => $j
                                                    ->on('datasets.id', '=', 'taggables.taggable_id')
                                                    ->where('taggables.taggable_type', 'App\\Models\\Dataset'))
                                                ->whereNotNull("datasets.{$dateField}")
                                                ->whereColumn('tags.id', 'taggables.tag_id'))
                      ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(tags.name, '$.en')) NOT IN ('OpenVisus', 'OpenVisus-Commons-Import')")
                      ->count();

        return view('public.index', [
            'communities'  => $communities,
            'isTest'       => $isTest,
            'datasetCount' => $datasetCount,
            'tagCount'     => $tagCount,
        ]);
    }

    /**
     * Return all published datasets for datatables
     * @return array
     */
    public function getAllPublishedDatasets()
    {
        return Laratables::recordsOf(Dataset::class, function ($query) {
            return $query->withCount('views', 'downloads')
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
                         ->whereDoesntHave('tags', function ($q) {
                             $q->where('tags.id', config('visus.import_tag_id'));
                         })
                         ->whereNotNull('test_published_at');
        });
    }
}
