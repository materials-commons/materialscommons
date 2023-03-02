<?php

namespace App\Http\Controllers\Web\Welcome;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use function view;

class WelcomeWebController extends Controller
{
    public function __invoke(Request $request)
    {
        return view('welcome');

//        $datasetsCount = Dataset::whereNotNull('published_at')
//                                ->whereDoesntHave('tags', function ($q) {
//                                    $q->where('tags.id', config('visus.import_tag_id'));
//                                })
//                                ->count();
//        $specialCollectionsDatasetsCount = Dataset::withAnyTags(['OpenVisus'])
//                                                  ->whereNotNull('published_at')
//
//
//     return view('welcome', [
//            'datasets'                   => $datasetsCount,
//            'specialCollectionsDatasets' => $specialCollectionsDatasetsCount,
//        ]);                                              ->count();;
    }
}
