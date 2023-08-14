<?php

namespace App\Helpers;

class Files
{
    # matter controller
    public static function is_existing($path)
    {
        # create folder is not exist
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
    }
}
