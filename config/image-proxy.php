<?php

return [
    'route_prefix' => env('IMAGE_PROXY_ROUTE_PREFIX', 'img'),
    'route_names' => [
        'image.proxy.short' => env('IMAGE_PROXY_ROUTE_SHORT', 'image.proxy.short'),
        'image.proxy.filename' => env('IMAGE_PROXY_ROUTE_FILENAME', 'image.proxy.filename'),
    ],
    'route_middleware' => [],
    'disks' => [
        'source' => env('IMAGE_PROXY_DISK_SOURCE', 'local'),
        'cache' => env('IMAGE_PROXY_DISK_CACHE', 'local'),
    ],
    'encryptor' => Bst27\ImageProxy\Services\OpenSslPayloadEncryptor::class,
    'token_encoder' => Bst27\ImageProxy\Services\Base64UrlTokenEncoder::class,
    'manipulation_strategy' => [
        'default' => [
            'class' => \Bst27\ImageProxy\Services\ImageManipulator\DefaultManipulator::class,
            'params' => [],
        ],
    ],
];
