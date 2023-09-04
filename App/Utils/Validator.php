<?php

class Validator
{
    public function __construct()
    {

    }

    // Validate empty fields and write error messages in session
    public static function validateEmptyField($var, string $field, string $message)
    {
        if (empty($var)) {
            static::addValidationError($field, $message);
        }
    }

    // Validate email field and write error messages in session
    public static function validateEmail(string $email, string $message = 'Incorrect email')
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            static::addValidationError('email', $message);
        }
    }

    public static function validateConfirmPassword(string $password, string $confirmPassword)
    {
        if ($confirmPassword !== $password) {
            static::addValidationError('password', "Passwords don't match");
        }
    }

    public static function validateFile(array $file, array $types, int $fileMaxSize)
    {
        if (!in_array($file['type'], $types)) {
            static::addValidationError('avatar', 'Incorrect file type');
        }

        if ($file['size'] / 1000000 >= $fileMaxSize) {
            static::addValidationError('avatar', "File size must be less than $fileMaxSize mb");
        }
    }

    public static function addValidationError(string $field, string $message)
    {
        $_SESSION['validation'] = [
            $field => $message,
        ];
    }

    public static function hasValidationError(string $field)
    {
        return isset($_SESSION['validation'][$field]);
    }

    public static function getErrorAttributes(string $field)
    {
        echo isset($_SESSION['validation'][$field]) ? "aria-invalid=true" : '';
    }

    public static function getErrorMessage(string $field)
    {
        echo $_SESSION['validation'][$field] ?? '';
    }

    public static function clearValidationSession()
    {
        $_SESSION['validation'] = [];
    }
}
