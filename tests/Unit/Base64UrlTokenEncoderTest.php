<?php

namespace Bst27\ImageProxy\Tests\Unit;

use Bst27\ImageProxy\Services\Base64UrlTokenEncoder;
use Bst27\ImageProxy\Tests\TestCase;

class Base64UrlTokenEncoderTest extends TestCase
{
    public function test_encode_decode_roundtrip()
    {
        $encoder = new Base64UrlTokenEncoder;
        $plain = 'hello-world-123';
        $encoded = $encoder->encode($plain);

        $this->assertNotEmpty($encoded);
        $this->assertNotSame($plain, $encoded);

        $decoded = $encoder->decode($encoded);
        $this->assertSame($plain, $decoded);
    }
}
