<?php

namespace App\Http\Controllers\Web\Published;

use App\Actions\Published\SearchPublishedDataAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SearchPublishedDataWebController extends Controller
{
    public function __invoke(Request $request, SearchPublishedDataAction $searchPublishedDataAction)
    {
        $search = $request->input('search');
        $searchResults = $searchPublishedDataAction($search);
        return view('public.search', compact('searchResults', 'search'));
    }
}
