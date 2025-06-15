<?php

namespace Bst27\ImageProxy\Contracts;

interface ImageManipulator
{
    /**
     * This function has to return the content of the manipulated file as string.
     * It is given the original file content and any additional parameters if available.
     */
    public function manipulate(string $fileContent, array $params): string;
}
