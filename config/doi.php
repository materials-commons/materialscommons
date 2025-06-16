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
    'test_dataset_url' => env('MC_TEST_DS_URL'),
    'test_namespace'   => env('DOI_TEST_NAMESPACE'),
    'test_user'        => env('DOI_TEST_USER'),
    'test_password'    => env('DOI_TEST_PASSWORD'),
    'test_service_url' => env('DOI_TEST_SERVICE_URL'),
];
