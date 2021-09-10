<?php

namespace Xanweb\Helper;

class Path
{
    /**
     * Get absolute path from relative.
     *
     * @param string $relativePath
     * @return string
     */
    public static function getAbsolutePath(string $relativePath): string
    {
        if (static::isAbsolutePath($relativePath)) {
            return $relativePath;
        }

        if (!starts_with($relativePath, ['/', '\\'])) {
            $relativePath = '/' . $relativePath;
        }

        return DIR_BASE . $relativePath;
    }

    /**
     * Check if path is absolute.
     *
     * @param string $path
     * @return bool
     */
    public static function isAbsolutePath(string $path): bool
    {
        return strpos(str_replace(DIRECTORY_SEPARATOR, '/', $path), DIR_BASE) !== false;
    }
}
