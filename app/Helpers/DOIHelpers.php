<?php

namespace App\Helpers;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class DOIHelpers
{
    public static function mintDOI($title, $author, $datasetId)
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

    public static function mintDOI2($title, $author, $datasetId)
    {
        // First create a draft DOI
        $response = Http::withBasicAuth(config('doi.user'), config('doi.password'))
                        ->contentType('application/vnd.api+json')
                        ->post('https://api.datacite.org/dois', [
                            'data' => [
                                'type'       => 'dois',
                                'attributes' => [
                                    'prefix' => config('doi.namespace'),
                                ],
                            ],
                        ]);
        if (!$response->ok()) {
            return null;
        }

        $DSURL = config('doi.dataset_url');
        $draft = $response->json()['data'];
        // Now that we have a draft DOI, publish it
        $response = Http::withBasicAuth(config('doi.user'), config('doi.password'))
                        ->contentType('application/vnd.api+json')
                        ->post('https://api.datacite.org/dois', [
                            'data' => [
                                'id'         => $draft['id'],
                                'type'       => 'dois',
                                'attributes' => [
                                    'event'           => 'publish',
                                    'doi'             => $draft['id'],
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

        if ($response->ok()) {
            return null;
        }

        return $draft['id'];
    }
}

