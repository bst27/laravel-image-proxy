<?php

namespace Bst27\ImageProxy\Contracts;

interface PayloadEncryptor
{
    public function encrypt(string $payload): string;
    public function decrypt(string $cipher): string;
}
