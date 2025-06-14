<?php

namespace Bst27\ImageProxy;

use Bst27\ImageProxy\Contracts\PayloadEncryptor;
use Bst27\ImageProxy\Contracts\TokenEncoder;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ImageProxyServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        $this->publishes([
            __DIR__.'/../config/image-proxy.php' => config_path('image-proxy.php'),
        ], 'config');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/image-proxy.php',
            'image-proxy'
        );

        $this->app->singleton(
            PayloadEncryptor::class,
            config('image-proxy.encryptor')
        );

        $this->app->singleton(
            TokenEncoder::class,
            config('image-proxy.token_encoder')
        );
    }
}
