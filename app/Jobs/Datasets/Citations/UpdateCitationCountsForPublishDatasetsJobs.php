<?php

namespace App\Jobs\Datasets\Citations;

use App\Models\Dataset;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Storage;
use function config;
use function is_null;
use const JSON_PRETTY_PRINT;

class UpdateCitationCountsForPublishDatasetsJobs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $cursor = Dataset::with('papers')
                         ->whereDoesntHave('tags', function ($q) {
                             $q->where('tags.id', config('visus.import_tag_id'));
                         })
                         ->whereNotNull('published_at')
                         ->cursor();
        foreach ($cursor as $ds) {
            if (!is_null($ds->doi)) {
                $doi = Str::after($ds->doi, "doi:");
                $citations = $this->getCitations($doi);
                if (!is_null($citations)) {
                    $this->saveCitationsToFileForDOI($ds, $citations, $doi);
                }
            }

            if ($ds->papers->isNotEmpty()) {
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
                        $citations = $this->getCitations($doi);
                        if (!is_null($citations)) {
                            $this->saveCitationsToFileForDOI($ds, $citations, $doi, true);
                        }
                    }
                }
            }
        }
    }

    private function getCitations($doi): mixed
    {
        $doiEncoded = urlencode($doi);
        $mailto = config('doi.crossref.mailto');
        $resp = Http::get("https://api.crossref.org/works/{$doiEncoded}?mailto={$mailto}");
        if ($resp->successful()) {
            return $resp->json();
        }
        return null;
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
