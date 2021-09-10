<?php

namespace Xanweb\Helper;

class Arr
{
    /**
     * Verify that all needles are in haystack array.
     *
     * @param array $needles
     * @param array $haystack
     *
     * @return bool
     */
    public static function inArrayAll(array $needles, array $haystack): bool
    {
        return array_diff($needles, $haystack) === [];
    }

    /**
     * Verify that at least one of needles is in haystack array.
     *
     * @param array $needles
     * @param array $haystack
     *
     * @return bool
     */
    public static function inArrayAny(array $needles, array $haystack): bool
    {
        return array_intersect($needles, $haystack) !== [];
    }
}
