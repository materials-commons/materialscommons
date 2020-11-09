<?php

namespace App\Http\Controllers\Web\Published\Authors;

use App\Actions\Published\SearchedPublishedDataAuthorsAction;
use App\Http\Controllers\Controller;
use App\ViewModels\Published\Datasets\ShowAuthorsPublishedDatasetsViewModel;
use Illuminate\Http\Request;

class SearchPublishedAuthorsWebController extends Controller
{

    public function __invoke(Request $request, SearchedPublishedDataAuthorsAction $searchedPublishedDataAuthorsAction)
    {
        $search = $request->input('search');
        $datasets = $searchedPublishedDataAuthorsAction($search);
        $viewModel = new ShowAuthorsPublishedDatasetsViewModel($datasets, $search);
        return view('public.authors.author-datasets', $viewModel);
    }
}
