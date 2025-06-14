<?php
namespace Bst27\ImageProxy\Services;

use Bst27\ImageProxy\Contracts\PayloadEncryptor;

class OpenSslPayloadEncryptor implements PayloadEncryptor
{
    private const HASH_ALGORITHM = 'sha256';
    private const CIPHER_ALGORITHM = 'AES-256-ECB';

    public function encrypt(string $payload): string
    {
        return openssl_encrypt(
            $payload,
            self::CIPHER_ALGORITHM,
            $this->getKey(),
            OPENSSL_RAW_DATA
        );
    }

    public function decrypt(string $cipher): string
    {
        return (string) openssl_decrypt(
            $cipher,
            self::CIPHER_ALGORITHM,
            $this->getKey(),
            OPENSSL_RAW_DATA
        );
    }

    private function getKey(): string
    {
        return hash(self::HASH_ALGORITHM, config('app.key'), true);
    }
}
