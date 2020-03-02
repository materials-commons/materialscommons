<?php

namespace App\Http\Controllers\Web\Published\Authors;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IndexPublishedAuthorsWebController extends Controller
{
    public function __invoke(Request $request)
    {
        // Not the most efficient way, but good enough for now.
        $datasets = DB::table('datasets')->select('authors')->distinct()->get();
        $authors = [];
        foreach ($datasets as $ds) {
            foreach (explode(';', $ds->authors) as $author) {
                if (isset($authors[$author])) {
                    $count = $authors[$author];
                    $count++;
                    $authors[$author] = $count;
                } else {
                    $authors[$author] = 1;
                }
            }
        }

        return view('public.authors.index', compact('authors'));
    }
}
