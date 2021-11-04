<?php

namespace App\Helpers;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class DOIHelpers
{
    public static function mintDOIEzAPI($title, $author, $datasetId)
    {
        $client = new Client();
        $year = Carbon::now()->year;
        $DSURL = config('doi.dataset_url');
        $serviceUrl = config('doi.service_url');
        $uri = "${serviceUrl}/shoulder/doi:".config('doi.namespace');
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
        return str_replace("doi:", "", $matches[0]);
    }

    public static function mintDOI($title, $author, $datasetId)
    {
        $doiServiceUrl = config('doi.service_url');
        $DSURL = config('doi.dataset_url');
        $response = Http::withBasicAuth(config('doi.user'), config('doi.password'))
                        ->contentType('application/vnd.api+json')
                        ->post($doiServiceUrl, [
                            'data' => [
                                'type'       => 'dois',
                                'attributes' => [
                                    'event'           => 'publish',
                                    'prefix'          => config('doi.namespace'),
                                    'creators'        => [
                                        ['name' => $author],
                                    ],
                                    'titles'          => [
                                        ['title' => $title],
                                    ],
                                    'publisher'       => 'Materials Commons',
                                    'publicationYear' => Carbon::now()->year,
                                    'types'           => [
                                        'resourceTypeGeneral' => 'Dataset',
                                    ],
                                    'url'             => "{$DSURL}/{$datasetId}",
                                ],
                            ],
                        ]);

        if (!$response->successful()) {
            return null;
        }

        return $response->json()['data']['id'];
    }
}

