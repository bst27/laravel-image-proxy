<?php

namespace Bst27\ImageProxy\Tests\Feature;

use Bst27\ImageProxy\Tests\TestCase;

class ImageProxyControllerTest extends TestCase
{
    public function test_serve_filename_rejects_wrong_filename()
    {
        $good = proxy_image('images/60x40.png', 'default', 'bar.png', []);
        $bad = preg_replace('/bar\.png$/', 'baz.png', $good);

        $this->get($bad)->assertStatus(404);
        $this->get($good)->assertStatus(200);
    }

    public function test_serve_short_rejects_wrong_extension()
    {
        $good = proxy_image('images/60x40.png');
        $bad = preg_replace('/\.png$/', '.dat', $good);

        $this->get($bad)->assertStatus(404);
        $this->get($good)->assertStatus(200);
    }

    public function test_invalid_token_returns404()
    {
        $this->get('/img/invalid-token-123.png')->assertStatus(404);
    }
}
