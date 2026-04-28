<?php

namespace App\Http\Controllers\Web\Published\Communities;

use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\Dataset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function config;

class IndexPublishedCommunitiesWebController extends Controller
{
    public function __invoke(Request $request)
    {
        $communities = Community::with([
                                    'owner',
                                    'publishedDatasets' => fn($q) => $q
                                        ->whereDoesntHave('tags', fn($tq) => $tq
                                            ->where('tags.id', config('visus.import_tag_id')))
                                        ->orderByDesc('published_at')
                                        ->select(['datasets.id', 'datasets.name', 'datasets.published_at']),
                                ])
                                ->withCount([
                                    'datasets as datasets_count' => fn($q) => $q
                                        ->whereDoesntHave('tags', fn($tq) => $tq
                                            ->where('tags.id', config('visus.import_tag_id')))
                                ])
                                ->where('public', true)
                                ->where('name', '<>', 'OpenVisus')
                                ->orderBy('name')
                                ->get();

        // Browse-strip counts
        $datasetCount = Dataset::whereNotNull('published_at')
                               ->whereDoesntHave('tags', function ($q) {
                                   $q->where('tags.id', config('visus.import_tag_id'));
                               })
                               ->count();

        $tagCount = DB::table('tags')
                      ->whereExists(fn($q) => $q->select(DB::raw(1))
                                                ->from('taggables')
                                                ->join('datasets', fn($j) => $j
                                                    ->on('datasets.id', '=', 'taggables.taggable_id')
                                                    ->where('taggables.taggable_type', 'App\\Models\\Dataset'))
                                                ->whereNotNull('datasets.published_at')
                                                ->whereColumn('tags.id', 'taggables.tag_id'))
                      ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(tags.name, '$.en')) NOT IN ('OpenVisus', 'OpenVisus-Commons-Import')")
                      ->count();

        return view('public.communities.index', compact('communities', 'datasetCount', 'tagCount'));
    }
}
