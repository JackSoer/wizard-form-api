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

    public static function validateRequireFields(array $requireFields, array $obj): array
    {
        $errors = [];

        foreach ($requireFields as $requireField) {
            if (!isset($obj[$requireField])) {
                $requireFieldWithFirstUpperCaseLetter = ucfirst($requireField);

                array_push($errors, "$requireFieldWithFirstUpperCaseLetter is required");
            }
        }

        return $errors;
    }

    public static function validateFields(array $validPattern, array $obj): array
    {
        $errors = [];

        foreach ($validPattern as $field => $pattern) {
            if (isset($obj[$field]) && !preg_match($pattern, $obj[$field])) {
                $validatedFieldWithFirstUpperCaseLetter = ucfirst($field);

                array_push($errors, "$validatedFieldWithFirstUpperCaseLetter is not valid");
            }
        }

        return $errors;
    }
}
