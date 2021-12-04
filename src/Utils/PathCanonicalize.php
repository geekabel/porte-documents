<?php

declare(strict_types=1);

namespace App\Utils;

use Webmozart\PathUtil\Path;

class PathCanonicalize
{
    public static function canonicalize(string $basePath, string $filename): string
    {
        $path = Path::canonicalize($basePath . DIRECTORY_SEPARATOR . $filename);

        // If the path is outside the `$basePath`, we do not allow it.
        if (mb_strpos(Path::makeRelative($path, $basePath), '..') === 0) {
            throw new \Exception('You are not allowed to access path ' . $path);
        }

        return $path;
    }
}