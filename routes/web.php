<?php

use Bst27\ImageProxy\Http\Controllers\ImageProxyController;
use Illuminate\Support\Facades\Route;

$prefix = config('image-proxy.route_prefix');
$names = config('image-proxy.route_names');
$middleware = config('image-proxy.route_middleware');

Route::group([
    'prefix' => $prefix,
    'middleware' => $middleware,
], function () use ($names) {
    Route::get('{token}.{ext}', [ImageProxyController::class, 'serveShort'])
        ->where('token', '[A-Za-z0-9\-_]+')
        ->where('ext', '[A-Za-z0-9]+')
        ->name($names['image.proxy.short']);

    Route::get('{token}/{filename}', [ImageProxyController::class, 'serveFilename'])
        ->where('token', '[A-Za-z0-9\-_]+')
        ->name($names['image.proxy.filename']);
});
