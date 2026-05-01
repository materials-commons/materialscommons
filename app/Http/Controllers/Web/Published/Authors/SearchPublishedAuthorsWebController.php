<?php

namespace App\Http\Controllers\Web\Published\Authors;

use App\Actions\Published\SearchPublishedDataAuthorsAction;
use App\Http\Controllers\Controller;
use App\ViewModels\Published\Datasets\ShowAuthorsPublishedDatasetsViewModel;
use Illuminate\Http\Request;

class SearchPublishedAuthorsWebController extends Controller
{

    public function __invoke(Request $request, SearchPublishedDataAuthorsAction $searchedPublishedDataAuthorsAction)
    {
        $search = $request->input('search');
        if (blank($search)) {
            return redirect(route('public.authors.index'));
        }
        $datasets = $searchedPublishedDataAuthorsAction($search);
        $viewModel = new ShowAuthorsPublishedDatasetsViewModel($datasets, $search);
        return view('public.authors.author-datasets', $viewModel);
    }
}
