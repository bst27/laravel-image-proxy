<?php

namespace Bst27\ImageProxy\Services\ImageManipulator;

use Bst27\ImageProxy\Contracts\ImageManipulator;
use Spatie\ImageOptimizer\OptimizerChainFactory;

class DefaultManipulator implements ImageManipulator
{
    public function manipulate(string $fileContent, array $params): string
    {
        $tmpFile  = tempnam(sys_get_temp_dir(), 'img');
        file_put_contents($tmpFile, $fileContent);

        try {
            $optimizer = OptimizerChainFactory::create();
            $optimizer->optimize($tmpFile);

            return file_get_contents($tmpFile);
        } finally {
            unlink($tmpFile);
        }
    }
}
