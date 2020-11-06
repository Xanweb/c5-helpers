<?php

namespace Xanweb\Helper;

class Path
{
    /**
     * Get absolute path from relative.
     *
     * @param string $relativePath
     */
    public static function getAbsolutePath(string $relativePath): string
    {
        if (static::isAbsolutePath($relativePath)) {
            return $relativePath;
        }

        if (!starts_with($relativePath, ['/', '\\'])) {
            $relativePath = DIRECTORY_SEPARATOR . $relativePath;
        }

        return DIR_BASE . $relativePath;
    }

    /**
     * Check if path is absolute.
     *
     * @param string $path
     */
    public static function isAbsolutePath(string $path): bool
    {
        return strpos($path, DIR_BASE) !== false;
    }
}
