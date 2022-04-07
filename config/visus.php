<?php

return [
    'url'      => env('VISUS_URL',
        'http://localhost:8080/viewer/viewer.html?server=http%3A%2F%2Flocalhost:8080%2Fmod_visus%3F&amp;dataset='),
    'idx_path' => env('VISUS_IDX_PATH', env('MCFS_DIR')."/open_visus"),
];