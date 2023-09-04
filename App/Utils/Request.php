<?php

session_start();

class Request
{
    public function __construct()
    {

    }

    // Redirect to specific path
    public static function redirect(string $path)
    {
        header(header: "Location: $path");
        die();
    }

    // Set old value in session
    public static function setOldValue(string $key, mixed $value)
    {
        $_SESSION['old'][$key] = $value;
    }

    // Get old value from session
    public static function getOldValue(string $key)
    {
        $value = $_SESSION['old'][$key] ?? '';

        unset($_SESSION['old'][$key]);

        return $value;
    }

    // Clear session key - old
    public static function clearOldValues()
    {
        $_SESSION['old'] = [];
    }
}
