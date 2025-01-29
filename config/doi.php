<?php

return [
    'dataset_url' => env('MC_DS_URL'),
    'namespace'   => env('DOI_NAMESPACE'),
    'user'        => env('DOI_USER'),
    'password'    => env('DOI_PASSWORD'),
    'service_url' => env('DOI_SERVICE_URL'),
    'crossref' => [
        'mailto' => env('CROSSREF_MAILTO'),
    ],
];
