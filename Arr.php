<?php

namespace Xanweb\Helper;

class Arr
{
    /**
     * Gets the first key of an array.
     *
     * @param array $array
     *
     * @return mixed
     */
    public static function firstKey(array $array)
    {
        foreach ($array as $key => $unused) {
            return $key;
        }

        return null;
    }

    /**
     * Gets the last key of an array.
     *
     * @param array $array
     *
     * @return mixed
     */
    public static function lastKey(array $array)
    {
        return key(array_slice($array, -1, 1, true));
    }

    /**
     * Verify that the contents of a variable is a countable value.
     *
     * @param mixed $value
     *
     * @return bool
     */
    public static function isCountable($value): bool
    {
        return is_array($value) || $value instanceof \Countable || $value instanceof \ResourceBundle || $value instanceof \SimpleXmlElement;
    }

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
        return empty(array_diff($needles, $haystack));
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
        return !empty(array_intersect($needles, $haystack));
    }
}
