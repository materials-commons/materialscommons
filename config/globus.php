<?php

return [
    'cc_user'   => env('MC_GLOBUS_CC_USER'),
    'cc_token'  => env('MC_GLOBUS_CC_TOKEN'),
    'endpoint'  => env("MC_GLOBUS_ENDPOINT_ID"),
    'max_items' => env('GLOBUS_MAX_ITEMS', 500),
];

