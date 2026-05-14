<?php

return [
    'make_command' => [
        'emoji' => false,
    ],
    'component_locations' => [
        resource_path('views/components'),
        resource_path('views/livewire'),
    ],
    'temporary_file_upload' => [
        'disk' => null,        // Example: 'local', 's3'              | Default: 'default'
        'rules' => ['required', 'file', 'max:262144'],        // Example: ['file', 'mimes:png,jpg']  | Default: ['required', 'file', 'max:12288'] (12MB)
        'directory' => null,   // Example: 'tmp'                      | Default: 'livewire-tmp'
        'middleware' => 'throttle:120,1',  // Example: 'throttle:5,1'             | Default: 'throttle:60,1'
        'preview_mimes' => [   // Supported file types for temporary pre-signed file URLs...
            'png',
            'gif',
            'bmp',
            'svg',
            'wav',
            'mp4',
            'mov',
            'avi',
            'wmv',
            'mp3',
            'm4a',
            'jpg',
            'jpeg',
            'mpga',
            'webp',
            'wma',
        ],
        'max_upload_time' => 15, // Max duration (in minutes) before an upload is invalidated...
        'cleanup' => true, // Should cleanup temporary uploads older than 24 hrs...
    ],
];
