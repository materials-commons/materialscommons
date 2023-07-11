<?php

namespace App\Http\Controllers\Web\Published\Communities;

use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\Dataset;
use App\ViewModels\Published\Datasets\ShowAuthorsPublishedDatasetsViewModel;
use Illuminate\Http\Request;

class SearchCommunityForDatasetsWithAuthorWebController extends Controller
{
    public function __invoke(Request $request, Community $community)
    {
        $search = $request->input('search');
        $datasets = Dataset::with(['owner', 'tags', 'rootDir'])
                           ->withCount('views', 'downloads')
                           ->whereNotNull('published_at')
                           ->whereIn('id', function ($q) use ($community) {
                               $q->select('dataset_id')
                                 ->from('community2dataset')
                                 ->where('community_id', $community->id);
                           })
                           ->where('authors', 'like', '%'.$search.'%')
                           ->get();
        $viewModel = new ShowAuthorsPublishedDatasetsViewModel($datasets, $search);
        return view('public.authors.author-datasets', $viewModel);
    }
}
