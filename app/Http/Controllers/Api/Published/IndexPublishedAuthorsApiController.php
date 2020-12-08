<?php

namespace App\Http\Controllers\Api\Published;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class IndexPublishedAuthorsApiController extends Controller
{
    public function __invoke(Request $request)
    {
        $publishedDatasets = Dataset::whereNotNull('published_at')->get();
        return $this->getPublishedDatasetAuthors($publishedDatasets);
    }

    private function getPublishedDatasetAuthors($publishedDatasets)
    {
        $merged = collect();
        $publishedDatasets->pluck('authors')
                          ->map(function ($authors) {
                              return Str::of($authors)->explode('; ')->map(function ($author) {
                                  return Str::of($author)->before('(')->trim()->__toString();
                              });
                          })
                          ->each(function ($authors) use (&$merged) {
                              $merged = $merged->merge($authors);
                          });

        return $merged->filter(function ($author) {
            return !blank($author);
        })
                      ->unique()
                      ->values();
    }
}
