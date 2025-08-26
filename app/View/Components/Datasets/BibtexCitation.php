<?php

namespace App\View\Components\Datasets;

use App\Models\Dataset;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class BibtexCitation extends Component
{
    public Dataset $dataset;

    public function __construct($dataset)
    {
        $this->dataset = $dataset;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        // Create the citation id by forming it from the username, year it
        // was published, and the dataset id to ensure uniqueness. Truncate
        // name to 8 characters to make sure it isn't too long. Names could
        // have non-ascii characters so strip those out.
        $namePieces = explode(" ", preg_replace("/[[:^print:]]/", "", $this->dataset->owner->name));
        // Get last array entry and treat as last name
        $lastName = Str::of(array_pop($namePieces))->trim()->limit(8);
        if (!blank($this->dataset->published_at)) {
            $id = "mc{$this->dataset->id}{$lastName}{$this->dataset->published_at->year}";
        } elseif (!blank($this->dataset->test_published_at)) {
            $id = "mc{$this->dataset->id}{$lastName}{$this->dataset->test_published_at->year}";
        } else {
            $id = "mc{$this->dataset->id}{$lastName}";
        }
        return view('components.datasets.bibtex-citation', ['id' => $id]);
    }
}
