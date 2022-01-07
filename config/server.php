<?php

return [
    'version'           => env('MC_SERVER_VERSION', 'Unknown'),
    'institution'       => env('MC_SERVER_INSTITUTION', 'Unknown'),
    'last_updated_at'   => env('MC_SERVER_LAST_UPDATED_AT', 'Unknown'),
    'first_deployed_at' => env('MC_SERVER_FIRST_DEPLOYED_AT', 'Unknown'),
    'contact'           => env('MC_SERVER_CONTACT', 'materials-commons-help@umich.edu'),
    'description'       => env('MC_SERVER_DESCRIPTION', 'Materials Commons server'),
    'uuid'              => env('MC_SERVER_UUID', 'Not Set'),
    'down_file'         => env('MC_DOWN_FILE', '/no/such/file'),
];
