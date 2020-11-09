<?php

namespace App\ViewModels\Published\Datasets;

use Illuminate\Support\Str;
use Spatie\ViewModels\ViewModel;

class ShowAuthorsPublishedDatasetsViewModel extends ViewModel
{
    private $datasets;
    private $author;

    public function __construct($datasets, $author)
    {
        $this->datasets = $datasets;
        $this->author = $author;
    }

    public function datasets()
    {
        return $this->datasets;
    }

    public function author()
    {
        return $this->author;
    }

    public function formatAuthors($authors)
    {
        return implode(", ", collect(explode(';', $authors))->map(function ($author) {
            return Str::of($author)->before('(')->trim()->__toString();
        })->toArray());
    }
}
