<?php

namespace Bst27\ImageProxy\Services;

use Bst27\ImageProxy\Contracts\TokenEncoder;

class Base64UrlTokenEncoder implements TokenEncoder
{
    public function encode(string $cipher): string
    {
        return rtrim(strtr(base64_encode($cipher), '+/', '-_'), '=');
    }

    public function decode(string $token): string
    {
        $b64 = strtr($token, '-_', '+/');

        return (string) base64_decode($b64);
    }
}
