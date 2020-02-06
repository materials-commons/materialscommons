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
        $DSURL = config('doi.dataset_url');
        $uri = "https://ez.test.datacite.org/shoulder/doi:".config('doi.namespace');
        $body = "_target:{$DSURL}/{$datasetId}\n".
            "datacite.creator: {$author}\n".
            "datacite.title: {$title}\n".
            "datacite.publisher: Materials Commons\n".
            "datacite.publicationyear: {$year}\n".
            "datacite.resourcetype: Dataset";
        $resp = $client->request('POST', $uri, [
            'headers' => ['Content-Type' => 'text/plain'],
            'body'    => $body,
            'auth'    => [config('doi.user'), config('doi.password')],
        ]);
        $respBody = (string) $resp->getBody();
        $matches = [];
        preg_match("/doi:\S*/", $respBody, $matches);
        return $matches[0];
    }
}

