<?php

declare (strict_types = 1);

namespace Api\Utils;

class Validator
{
    public function __construct()
    {

    }

    public static function validateFile(array $file, array $types, int $fileMaxSize)
    {
        if (!in_array($file['type'], $types) || $file['size'] / 1000000 >= $fileMaxSize) {
            return false;
        }

        return true;
    }
}
