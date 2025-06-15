<?php

namespace Bst27\ImageProxy\Tests;

use Bst27\ImageProxy\ImageProxyServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            ImageProxyServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        // Use local disk for source images, pointing at tests/Fixtures
        $app['config']->set('image-proxy.disks.source', 'fixtures');
        $app['config']->set('filesystems.disks.fixtures', [
            'driver' => 'local',
            'root'   => __DIR__ . '/Fixtures',
        ]);
    }
}
