<?php

namespace Bst27\ImageProxy\Tests\Unit;

use Bst27\ImageProxy\Services\ImageManipulator\DefaultManipulator;
use Bst27\ImageProxy\Tests\TestCase;
use Illuminate\Support\Facades\Storage;

class DefaultManipulatorTest extends TestCase
{
    public function testManipulateReturnsCompressedPng()
    {
        $binary = Storage::disk('fixtures')->get('images/60x40.png');

        $manipulator = new DefaultManipulator();
        $result      = $manipulator->manipulate($binary, []);

        $this->assertIsString($result);
        $this->assertNotEmpty($result);
        $this->assertStringStartsWith("\x89PNG", $result);
    }
}
