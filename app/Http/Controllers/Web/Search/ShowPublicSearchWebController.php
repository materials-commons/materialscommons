<?php

namespace App\Http\Controllers\Web\Search;

use App\Http\Controllers\Controller;

class ShowPublicSearchWebController extends Controller
{
    public function __invoke()
    {
        return view('app.search.public');
    }
}
