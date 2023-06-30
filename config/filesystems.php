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
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
        ],

        'upload_photo_fiche' => [
            'driver' => 'local',
            'root' => public_path('/upload_photo_fiche'),
            'url' => env('APP_URL').'/upload_photo_fiche',
            'visibility' => 'public',
        ],

        'image_page_accueil' => [
            'driver' => 'local',
            'root' => public_path('/configuration/image_page_accueil'),
            'url' => env('APP_URL').'/configuration/image_page_accueil',
            'visibility' => 'public',
        ],

        'logo' => [
            'driver' => 'local',
            'root' => public_path('/configuration/logo'),
            'url' => env('APP_URL').'/configuration/logo',
            'visibility' => 'public',
        ],

        'logo_partenaires' => [
            'driver' => 'local',
            'root' => public_path('/configuration/logo_partenaires'),
            'url' => env('APP_URL').'/configuration/logo_partenaires',
            'visibility' => 'public',
        ],

        'region_geojson' => [
            'driver' => 'local',
            'root' => public_path('/configuration/region_geojson'),
            'url' => env('APP_URL').'/configuration/region_geojson',
            'visibility' => 'public',
        ],

    ],

];
