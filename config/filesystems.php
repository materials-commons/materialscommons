<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => env('FILESYSTEM_CLOUD', 's3'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3", "rackspace"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root'   => storage_path('app'),
        ],

        'public' => [
            'driver'     => 'local',
            'root'       => storage_path('app/public'),
            'url'        => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        'mcfs' => [
            'driver' => 'local',
            'root'   => env('MCFS_DIR', storage_path('app/mcfs')),
        ],

        'mcfs_replica' => [
            'driver' => 'local',
            'root'   => env('MCFS_REPLICA_DIR'),
        ],

        'mcfs_backup' => [
            'driver' => 'local',
            'root'   => env('MCFS_BACKUP_DIR', '/mcfs/backups'),
        ],

        'etc' => [
            'driver' => 'local',
            'root'   => env('ETC_DIR', 'app/etc'),
        ],

        'local_backup' => [
            'driver' => 'local',
            'root'   => env('LOCAL_BACKUP_DIR', '/home/backups'),
        ],

        'test_data' => [
            'driver' => 'local',
            'root'   => storage_path('test_data'),
        ],

        's3' => [
            'driver'   => 's3',
            'key'      => env('S3_ACCESS_KEY_ID'),
            'secret'   => env('S3_SECRET_ACCESS_KEY'),
            'region'   => env('S3_DEFAULT_REGION'),
            'bucket'   => env('S3_BUCKET'),
            'endpoint' => env('S3_ENDPOINT'),
        ],

    ],

];
