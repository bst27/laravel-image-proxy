<?php

use Bst27\ImageProxy\Contracts\PayloadEncryptor;
use Bst27\ImageProxy\Contracts\TokenEncoder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

if (! function_exists('proxy_image')) {

    function proxy_image(
        string $path,
        ?string $strategyKey = 'default',
        false|string $fileName = false,
        array $mergeParams = []
    ): string {
        $sourceDisk = Storage::disk(config('image-proxy.disks.source'));

        if (! $sourceDisk->exists($path)) {
            throw new \InvalidArgumentException("Image [$path] not found");
        }

        $fileContent = $sourceDisk->get($path);
        $fileHash = md5($fileContent);

        $payload = [
            'path' => $path,
            'strategy' => $strategyKey,
            'mergeParams' => $mergeParams,
            'v' => $fileHash,
            'filename' => $fileName,
        ];

        $json = json_encode($payload, JSON_UNESCAPED_SLASHES);
        $encryptor = app(PayloadEncryptor::class);
        $cipher = $encryptor->encrypt($json);
        $encoder = app(TokenEncoder::class);
        $token = $encoder->encode($cipher);

        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $routeName = $fileName === false
            ? 'image.proxy.short'
            : 'image.proxy.filename';

        $routeParams = $fileName === false
            ? ['token' => $token, 'ext' => $ext]
            : ['token' => $token, 'filename' => $fileName];

        return URL::route($routeName, $routeParams);
    }
}
