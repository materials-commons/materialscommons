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

class GetAndSavePublishedDatasetCitationsAction
{
    public function execute(Dataset $ds)
    {
        if (!is_null($ds->doi)) {
            $doi = Str::after($ds->doi, "doi:");
            $citations = CrossrefApiService::getCitationForDOI($doi);
            if (!is_null($citations)) {
                $this->saveCitationsToFileForDOI($ds, $citations, $doi);
            }
        }

        if (isset($ds->papers) && $ds->papers->isNotEmpty()) {
            foreach ($ds->papers as $paper) {
                $doi = "";
                if (!is_null($paper->doi)) {
                    $doi = Str::after($paper->doi, "doi:");
                } elseif (Str::contains($paper->url, "doi")) {
                    $parts = explode("/", $paper->url);
                    $pieceLast = array_pop($parts);
                    $piece2ndToLast = array_pop($parts);
                    $doi = "{$piece2ndToLast}/{$pieceLast}";
                }

                if (!blank($doi)) {
                    $citations = CrossrefApiService::getCitationForDOI($doi);
                    if (!is_null($citations)) {
                        $this->saveCitationsToFileForDOI($ds, $citations, $doi, true);
                    }
                }
            }
        }
    }

    private function saveCitationsToFileForDOI($dataset, $citations, $doi, $isPaper = false): void
    {
        $str = json_encode($citations, JSON_PRETTY_PRINT);
        $doiUnderscore = Str::replace("/", "_", $doi);
        if ($isPaper) {
            $filepath = "{$dataset->publishedGlobusPath()}/paper_{$doiUnderscore}.json";
        } else {
            $filepath = "{$dataset->publishedGlobusPath()}/{$doiUnderscore}.json";
        }

        file_put_contents($filepath, $str);
    }
}