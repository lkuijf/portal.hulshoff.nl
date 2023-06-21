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

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been set up for each driver as an example of the required values.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
            'throw' => false,
        ],

        'local_xml_klant' => [
            'driver' => 'local',
            'root' => storage_path('app/xml/inbound/klant'),
            'throw' => false,
        ],
        'local_xml_artikel' => [
            'driver' => 'local',
            'root' => storage_path('app/xml/inbound/artikel'),
            'throw' => false,
        ],
        'local_xml_order' => [
            'driver' => 'local',
            'root' => storage_path('app/xml/inbound/order'),
            'throw' => false,
        ],
        'local_xml_vrdstand' => [
            'driver' => 'local',
            'root' => storage_path('app/xml/inbound/vrdstand'),
            'throw' => false,
        ],
        'local_xml_order_out' => [
            'driver' => 'local',
            'root' => storage_path('app/xml/outbound/order'),
            'throw' => false,
        ],

        'local_xml_klant_archived' => [
            'driver' => 'local',
            'root' => storage_path('app/xml/archived/inbound/klant'),
            'throw' => false,
        ],
        'local_xml_artikel_archived' => [
            'driver' => 'local',
            'root' => storage_path('app/xml/archived/inbound/artikel'),
            'throw' => false,
        ],
        'local_xml_order_archived' => [
            'driver' => 'local',
            'root' => storage_path('app/xml/archived/inbound/order'),
            'throw' => false,
        ],
        'local_xml_vrdstand_archived' => [
            'driver' => 'local',
            'root' => storage_path('app/xml/archived/inbound/vrdstand'),
            'throw' => false,
        ],
        'local_xml_order_out_archived' => [
            'driver' => 'local',
            'root' => storage_path('app/xml/archived/outbound/order'),
            'throw' => false,
        ],

        // 'local_pdf_exports' => [
        //     'driver' => 'local',
        //     'root' => storage_path('app/pdf'),
        //     'throw' => false,
        // ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
            'throw' => false,
        ],

        'tiles' => [
            'driver' => 'local',
            'root' => storage_path('app/public/tiles'),
            'url' => env('APP_URL').'/storage/tiles',
            'visibility' => 'public',
            'throw' => false,
        ],
        'csv' => [
            'driver' => 'local',
            'root' => storage_path('app/csv'),
            'url' => env('APP_URL').'/csv',
            'visibility' => 'public',
            'throw' => false,
        ],
        // 'pdf' => [
        //     'driver' => 'local',
        //     'root' => storage_path('app/public/pdf'),
        //     'url' => env('APP_URL').'/storage/pdf',
        //     'visibility' => 'public',
        //     'throw' => false,
        // ],
        // 'product_images_drive' => [
        //     'driver' => 'local',
        //     'root' => 'M:/',
        //     'url' => env('APP_URL').'/product_images_drive',
        //     'visibility' => 'public',
        //     'throw' => false,
        //     'readonly' => true,
        // ],
        // 'samba_drive' => [
        //     'driver' => 'smb',
        //     'host' => '192.168.110.37',
        //     'username' => 'opslag-user',
        //     'password' => 'casa123!',
        //     'share' => 'applications$',
        // ],
        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
        public_path('storage/tiles') => storage_path('app/public/tiles'),
        public_path('pdf') => storage_path('app/pdf'),
        public_path('csv') => storage_path('app/csv'),
        public_path('product_images') => 'M:/',
    ],

];
