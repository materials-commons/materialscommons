<?php

namespace App\Actions\Datasets;

use App\Models\Dataset;
use App\Services\CrossrefApiService;
use Illuminate\Support\Str;
use function array_pop;
use function blank;
use function explode;
use function file_put_contents;
use function is_null;
use function json_encode;
use const JSON_PRETTY_PRINT;
use const JSON_UNESCAPED_SLASHES;

class CitationsX
{
    public $dataset;
    public $papers;

    public function __construct()
    {
        $this->papers = [];
        $this->dataset = new \stdClass();
    }
}

class GetAndSavePublishedDatasetCitationsAction
{
    public function execute(Dataset $ds)
    {
        $hasCitations = false;
        $citation = new CitationsX();

        if (!blank($ds->doi)) {
            $doi = Str::after($ds->doi, "doi:");
            $citations = CrossrefApiService::getCitationForDOI($doi);

            if (!is_null($citations)) {
                $hasCitations = true;
                $citation->dataset = $citations;
            }
        }

        if (isset($ds->papers) && $ds->papers->isNotEmpty()) {
            foreach ($ds->papers as $paper) {
                $doi = "";
                if (!blank($paper->doi)) {
                    $doi = Str::after($paper->doi, "doi:");
                } elseif (!blank($paper->url) && Str::contains($paper->url, "doi")) {
                    $parts = explode("/", $paper->url);
                    $pieceLast = array_pop($parts);
                    $piece2ndToLast = array_pop($parts);
                    $doi = "{$piece2ndToLast}/{$pieceLast}";
                }

                if (!blank($doi)) {
                    $citations = CrossrefApiService::getCitationForDOI($doi);
                    if (!is_null($citations)) {
                        $hasCitations = true;
                        $citation->papers[] = $citations;
                    }
                }
            }
        }

        if ($hasCitations) {
            $this->saveCitationsToFile($ds, $citation);
        }
    }

    private function saveCitationsToFile($dataset, $citationsObj): void
    {
        $str = json_encode($citationsObj, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $dataset->ensurePublishedGlobusPath();
        $filepath = "{$dataset->publishedGlobusPath()}/citations.json";
        file_put_contents($filepath, $str);
    }
}