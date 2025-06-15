# Laravel Image Proxy

[![Latest Version on Packagist](https://img.shields.io/packagist/v/bst27/laravel-image-proxy.svg?style=flat-square)](https://packagist.org/packages/bst27/laravel-image-proxy)
![Tests](https://github.com/bst27/laravel-image-proxy/workflows/CI/badge.svg)
[![Total Downloads](https://img.shields.io/packagist/dt/bst27/laravel-image-proxy.svg?style=flat-square)](https://packagist.org/packages/bst27/laravel-image-proxy)

A Laravel image proxy to cache, minify and manipulate images very easily.

## Features
- Automatic image compression / minification
- Automatic caching of images on host and client side
- Flexible storage options (local, S3, SFTP etc.)
- Optional: image manipulation (resizing etc.)
- Optional: custom image filename in URL
- Easily extendable with custom strategies for image manipulation

---

## Installation

```bash
composer require bst27/laravel-image-proxy
```

Per default [spatie/image-optimizer](https://github.com/spatie/image-optimizer) is used to compress images.
So make sure you install the required dependencies as described in their docs.

---

## Usage

Use the global helper function `proxy_image()` to generate a secure image URL:

```php
<img src="{{ proxy_image('images/example.jpg') }}" alt="Example">
```

This will automatically minify and cache the image and generate something like this:

```html
<img src="http://localhost/img/1AXe...S11lg.jpg" alt="Example">
```

You can also define a strategy and filename for image manipulation:

```php
<img src="{{ proxy_image('images/example.jpg', 'default', 'cat.jpg') }}" alt="A black cat">
```

This will use the `default` strategy for image manipulation, keep the given filename
and generate something like this:

```php
<img src="http://localhost/img/1AX5...3c1KAw/cat.jpg" alt="A black cat">
```

---

## Manipulation Strategies

To manipulate images, you can configure different strategies in [config/image-proxy.php](config/image-proxy.php).
The [DefaultManipulator](src/Services/ImageManipulator/DefaultManipulator.php) strategy uses
[spatie/image-optimizer](https://github.com/spatie/image-optimizer) to compress images.

```php
'manipulation_strategy' => [
    'default' => [
        'class'  => \Bst27\ImageProxy\Services\ImageManipulator\DefaultManipulator::class,
        'params' => [],
    ],
],
```

Each strategy class must implement the [ImageManipulator](src/Contracts/ImageManipulator.php) contract.
You can add your own image manipulation strategy easily:
1. Implement the contract interface
2. Add your manipulator to the `manipulation_strategy` array of the `image-proxy.php` with a unique strategy key.
3. Start using it by calling `proxy_image()` with your strategy key.

---

## Endpoints

Two routes are registered automatically:

- Short URL:  
  `/img/{token}.{ext}`

- Named URL:  
  `/img/{token}/{filename}`

URLs to these routes are generated via `proxy_image()` and require a valid encrypted payload token.
You can customize them via the config.

---

## Storage

[Flysystem](https://github.com/thephpleague/flysystem) is used to offer flexible storage options. Per default
the original images are read from the `local` storage disk. The cached images are stored on the `local` storage disk, too.
You can customize the used storage disks using the plugin config or environment settings.

---

## Configuration

Check [image-proxy.php](config/image-proxy.php) for default config. You can customize
config via environment variables or publish the config file:

```bash
php artisan vendor:publish --provider="Bst27\ImageProxy\ImageProxyServiceProvider"
```

This will create `config/image-proxy.php`.

---

## Tests

To run tests, you can execute the following command:

```bash
docker run --rm -it \
  -u "$(id -u):$(id -g)" \
  -v "$PWD":/var/www/html \
  -w /var/www/html \
  laravelsail/php84-composer:latest \
  php vendor/bin/phpunit
```
