<?php

namespace App\Helpers;

use Carbon\Carbon;
use GuzzleHttp\Client;

class DOIHelpers
{
    public static function mintDOI($title, $author, $datasetId)
    {
        $client = new Client();
        $year = Carbon::now()->year;
        $DSURL = env('MC_DS_URL');
        $uri = "https://ez.test.datacite.org/shoulder/doi:".env('DOI_NAMESPACE');
        $body = "_target:{$DSURL}/{$datasetId}\n".
            "datacite.creator: {$author}\n".
            "datacite.title: {$title}\n".
            "datacite.publisher: Materials Commons\n".
            "datacite.publicationyear: {$year}\n".
            "datacite.resourcetype: Dataset";
        $resp = $client->request('POST', $uri, [
            'headers' => ['Content-Type' => 'text/plain'],
            'body'    => $body,
            'auth'    => [env('DOI_USER'), env('DOI_PASSWORD')],
        ]);
        $respBody = (string) $resp->getBody();
        $matches = [];
        preg_match("/doi:\S*/", $respBody, $matches);
        return $matches[0];
    }
}

