<?php

declare (strict_types = 1);

namespace Api\Utils;

use Exception;

class FileManager
{
    public function __construct()
    {
    }

    // Upload file from temp directory
    public static function uploadFile(array $file, string $newFileName, string $storagePath)
    {
        if (!is_dir($storagePath)) {
            mkdir($storagePath, 0777, true);
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = $newFileName . "_" . time() . ".$extension";

        if (!move_uploaded_file($file['tmp_name'], "$storagePath/$fileName")) {
            throw new Exception("File wasn't loaded");
        }

        return "storage/$fileName";
    }
}
