<?php

namespace App\Http\Controllers\Web\Published\Authors;

use App\Actions\Published\SearchedPublishedDataAuthorsAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SearchPublishedAuthorsWebController extends Controller
{

    public function __invoke(Request $request, SearchedPublishedDataAuthorsAction $searchedPublishedDataAuthorsAction)
    {
        $search = $request->input('search');
        $searchResults = $searchedPublishedDataAuthorsAction($search);
        return view('public.search', compact('searchResults', 'search'));
    }
}
