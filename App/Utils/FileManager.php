<?php

class FileManager
{
    public function __construct()
    {
    }

    // Upload file from temp directory
    public static function uploadFile(array $file, string $storagePath)
    {
        if (!is_dir($storagePath)) {
            mkdir($storagePath, 0777, true);
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = 'avatar' . time() . ".$extension";

        if (!move_uploaded_file($file['tmp_name'], "$storagePath/$fileName")) {
            Validator::addValidationError('avatar', "File wasn't uploaded");
            Request::redirect("../../views/register.php");
        }

        return "storage/$fileName";
    }
}
