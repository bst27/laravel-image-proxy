<?php

namespace Bst27\ImageProxy\Contracts;

interface TokenEncoder
{
    public function encode(string $cipher): string;
    public function decode(string $token): string;
}
