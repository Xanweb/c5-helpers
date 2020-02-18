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
        return (!empty($array)) ? array_keys($array)[count($array) - 1] : null;
    }
}
