<?php

namespace Bst27\ImageProxy\Tests\Unit;

use Bst27\ImageProxy\Tests\TestCase;

class HelperTest extends TestCase
{
    public function test_proxy_image_generates_valid_url()
    {
        $url = proxy_image('images/60x40.jpg');
        $this->assertStringStartsWith('http', $url);
        $this->assertStringContainsString('/img/', $url);
        $this->assertStringEndsWith('.jpg', $url);
    }

    public function test_proxy_image_generates_valid_url_with_filename()
    {
        $url = proxy_image('images/60x40.jpg', fileName: 'foo.jpg');
        $this->assertStringStartsWith('http', $url);
        $this->assertStringContainsString('/img/', $url);
        $this->assertStringEndsWith('foo.jpg', $url);
    }
}
