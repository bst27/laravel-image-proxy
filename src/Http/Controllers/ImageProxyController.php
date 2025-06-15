<?php

namespace Bst27\ImageProxy\Http\Controllers;

use Bst27\ImageProxy\Contracts\ImageManipulator;
use Bst27\ImageProxy\Contracts\PayloadEncryptor;
use Bst27\ImageProxy\Contracts\TokenEncoder;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ImageProxyController extends Controller
{
    public function serveShort(string $token, string $ext): Response
    {
        $data = $this->decryptPayload($token);

        $actualExt = pathinfo($data['path'], PATHINFO_EXTENSION);
        if (strtolower($ext) !== strtolower($actualExt)) {
            abort(404);
        }

        return $this->processAndDeliver($data);
    }

    public function serveFilename(string $token, string $filename): Response
    {
        $data = $this->decryptPayload($token);

        $expectedName = pathinfo($data['filename'], PATHINFO_BASENAME);
        if ($filename !== $expectedName) {
            abort(404);
        }

        return $this->processAndDeliver($data);
    }

    private function decryptPayload(string $token): array
    {
        $encoder = app(TokenEncoder::class);
        $cipher = $encoder->decode($token);
        $encryptor = app(PayloadEncryptor::class);
        $json = $encryptor->decrypt($cipher);

        $data = json_decode($json, true);
        if (! $data || ! isset($data['path'], $data['v'])) {
            abort(404);
        }

        return $data;
    }

    private function processAndDeliver(array $data): Response
    {
        $sourceDisk = Storage::disk(config('image-proxy.disks.source'));
        $cacheDisk = Storage::disk(config('image-proxy.disks.cache'));

        $sourcePath = $data['path'];

        if (! $sourceDisk->exists($sourcePath)) {
            abort(404);
        }

        $fileContent = $sourceDisk->get($sourcePath);
        $fileHash = md5($fileContent);
        if ($fileHash !== $data['v']) {
            abort(404);
        }

        $strategyKey = $data['strategy'];
        $strategies = config('image-proxy.manipulation_strategy');

        if (! isset($strategies[$strategyKey])) {
            abort(500, "Unknown image-proxy strategy: {$strategyKey}");
        }

        $conf = $strategies[$strategyKey];
        $class = $conf['class'];
        $default = $conf['params'] ?? [];
        $mergeParams = $data['mergeParams'] ?? [];
        $params = array_merge($default, $mergeParams);

        $ext = pathinfo($sourcePath, PATHINFO_EXTENSION);
        $cacheHash = $fileHash.'-'.md5(json_encode($params));
        $cacheKey = $cacheHash.'.'.$ext;

        if (! $cacheDisk->exists($cacheKey)) {
            /** @var ImageManipulator $manipulator */
            $manipulator = app($class);
            $fileContent = $manipulator->manipulate($fileContent, $params);

            $cacheDisk->put($cacheKey, $fileContent);
        }

        $stream = $cacheDisk->readStream($cacheKey);

        return new StreamedResponse(function () use ($stream) {
            fpassthru($stream);
        }, 200, [
            'Content-Type' => $cacheDisk->mimeType($cacheKey),
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }
}
