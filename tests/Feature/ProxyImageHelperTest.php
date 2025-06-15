<?php

namespace Bst27\ImageProxy\Tests\Feature;

use Bst27\ImageProxy\Tests\TestCase;

class ProxyImageHelperTest extends TestCase
{
    public function test_helper_and_default_manipulator_integration()
    {
        $url = proxy_image('images/60x40.jpg', 'default', false, []);

        $response = $this->get($url);

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'image/jpeg');
        $this->assertNotEmpty($response->streamedContent());
    }
}
