<?php
namespace Xanweb\Helper;

class Path
{
    /**
     * Get absolute path from relative.
     *
     * @param string $relativePath  relative path
     *
     * @return string
     */
    public static function getAbsolutePath($relativePath)
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
     * @param  string $path
     *
     * @return string
     */
    public static function isAbsolutePath($path)
    {
        return strpos($path, DIR_BASE) !== false;
    }
}
