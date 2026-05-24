<?php

namespace App\Http\Controllers\Web\Published\Authors;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class IndexPublishedAuthorsWebController extends Controller
{
    public function __invoke(Request $request)
    {
        $datasets = Dataset::whereNotNull('published_at')->get(['id', 'name', 'ds_authors', 'published_at']);

        $authors = [];
        foreach ($datasets as $ds) {
            if (is_null($ds->ds_authors)) {
                continue;
            }
            $pubDate = $ds->published_at;
            $dsEntry = ['id' => $ds->id, 'title' => $ds->name];
            foreach ($ds->ds_authors as $author) {
                $name = Str::of($author['name'])->trim()->__toString();
                if ($name === '') {
                    continue;
                }
                if (!isset($authors[$name])) {
                    $authors[$name] = ['count' => 0, 'latest' => null, 'since' => null, 'user' => null, 'affiliations' => null, 'datasets' => []];
                }
                $authors[$name]['count']++;
                $authors[$name]['datasets'][] = $dsEntry;
                // Capture first non-empty affiliation seen in ds_authors
                if (empty($authors[$name]['affiliations']) && !empty($author['affiliations'])) {
                    $authors[$name]['affiliations'] = trim($author['affiliations']);
                }
                if ($pubDate) {
                    if (is_null($authors[$name]['latest']) || $pubDate > $authors[$name]['latest']) {
                        $authors[$name]['latest'] = $pubDate;
                    }
                    if (is_null($authors[$name]['since']) || $pubDate < $authors[$name]['since']) {
                        $authors[$name]['since'] = $pubDate;
                    }
                }
            }
        }

        $authors = collect($authors)
            ->sortKeys()
            ->toArray();

        // Resolve MC accounts so the view can link to profile pages
        $mcUsers = User::whereIn('name', array_keys($authors))->get(['id', 'name', 'slug', 'affiliations']);
        foreach ($mcUsers as $mcUser) {
            if (isset($authors[$mcUser->name])) {
                $authors[$mcUser->name]['user'] = $mcUser;
            }
        }

        // Per-dataset author counts for the distribution chart (dataset-centric view)
        $datasetAuthorCounts = [];
        foreach ($datasets as $ds) {
            if (is_null($ds->ds_authors)) {
                continue;
            }
            $count = count(array_filter($ds->ds_authors, fn($a) => trim($a['name'] ?? '') !== ''));
            if ($count > 0) {
                $datasetAuthorCounts[$ds->id] = ['title' => $ds->name, 'author_count' => $count];
            }
        }

        return view('public.authors.index', compact('authors', 'datasetAuthorCounts'));
    }
}
