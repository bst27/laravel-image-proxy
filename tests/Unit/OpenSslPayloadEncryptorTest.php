<?php

namespace Bst27\ImageProxy\Tests\Unit;

use Bst27\ImageProxy\Services\OpenSslPayloadEncryptor;
use Bst27\ImageProxy\Tests\TestCase;

class OpenSslPayloadEncryptorTest extends TestCase
{
    public function test_encrypt_decrypt_roundtrip()
    {
        $encryptor = new OpenSslPayloadEncryptor;

        $payload = json_encode(['foo' => 'bar', 'baz' => 42]);
        $cipher = $encryptor->encrypt($payload);

        $this->assertNotEmpty($cipher);
        $this->assertNotSame($payload, $cipher);

        $result = $encryptor->decrypt($cipher);
        $this->assertSame($payload, $result);
    }
}
