<?php

namespace Bst27\ImageProxy\Tests\Unit;

use Bst27\ImageProxy\Tests\TestCase;

class HelperTest extends TestCase
{
    public function testProxyImageGeneratesValidUrl()
    {
        $url = proxy_image('images/60x40.jpg');
        $this->assertStringStartsWith('http', $url);
        $this->assertStringContainsString('/img/', $url);
        $this->assertStringEndsWith('.jpg', $url);
    }

    public function testProxyImageGeneratesValidUrlWithFilename()
    {
        $url = proxy_image('images/60x40.jpg', fileName: 'foo.jpg');
        $this->assertStringStartsWith('http', $url);
        $this->assertStringContainsString('/img/', $url);
        $this->assertStringEndsWith('foo.jpg', $url);
    }
}
