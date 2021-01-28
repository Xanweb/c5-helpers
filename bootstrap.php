<?php

use Concrete\Core\Multilingual\Page\Section\Section;
use Concrete\Core\Support\Facade\Application;
use Xanweb\Helper\Arr;
use Xanweb\Helper\Path;
use Xanweb\Helper\Str;

if (!function_exists('c5app')) {
    /**
     * Resolve the given type from the container.
     *
     * @param string|null $abstract
     * @param array $parameters
     *
     * @return mixed
     */
    function c5app(?string $abstract = null, array $parameters = [])
    {
        $app = Application::getFacadeApplication();

        if ($abstract === null) {
            return $app;
        }

        return $app->make($abstract, $parameters);
    }
}

if (!function_exists('strip_spaces')) {
    /**
     * Remove all spaces from the given string.
     *
     * @param string $string
     *
     * @return string
     */
    function strip_spaces(string $string): string
    {
        return Str::stripSpaces($string);
    }
}

if (!function_exists('remove_accents')) {
    /**
     * Replace special chars with normal ones.
     *
     * @param string $string with accents
     *
     * @return string
     */
    function remove_accents(string $string): string
    {
        return Str::removeAccents($string);
    }
}

if (!function_exists('absolute_path')) {
    /**
     * Get absolute path from relative.
     *
     * @param string $relativePath relative path
     *
     * @return string
     */
    function absolute_path(string $relativePath): string
    {
        return Path::getAbsolutePath($relativePath);
    }
}

if (!function_exists('is_absolute_path')) {
    /**
     * Check if path is absolute.
     *
     * @param string $path
     *
     * @return bool
     */
    function is_absolute_path(string $path): bool
    {
        return Path::isAbsolutePath($path);
    }
}

if (!function_exists('theme_path')) {
    /**
     * Get theme relative path.
     *
     * @return string
     */
    function theme_path(): string
    {
        static $themePath;

        if (!$themePath) {
            $themePath = PageTheme::getSiteTheme()->getThemeURL();
        }

        return $themePath;
    }
}

if (!function_exists('active_language')) {
    /**
     * Get Active Contextual Language en|de...
     *
     * @return string
     */
    function active_language(): string
    {
        return Localization::activeLanguage();
    }
}

if (!function_exists('active_locale')) {
    /**
     * Get Active Contextual Locale en_US|de_DE...
     *
     * @return string
     */
    function active_locale(): string
    {
        return Localization::activeLocale();
    }
}

if (!function_exists('current_language')) {
    /**
     * Get Active Site Language en|de...
     *
     * @return string
     */
    function current_language(): string
    {
        return \current(\explode('_', current_locale()));
    }
}

if (!function_exists('current_locale')) {
    /**
     * Get Current Page Locale.
     *
     * @return string
     */
    function current_locale(): string
    {
        $section = Section::getCurrentSection();
        $locale = is_object($section) ? $section->getLocale() : null;

        return $locale ?? Localization::activeLocale();
    }
}

if (!function_exists('getRandomItemByInterval')) {
    /**
     * Get random item that its position is between zero and the maximal value (ItemsCount).
     *
     * @param $timeBase
     * @param $array
     *
     * @return mixed
     */
    function getRandomItemByInterval($timeBase, $array)
    {
        $randomIndexPos = (((int) $timeBase) % count($array));

        return $array[$randomIndexPos];
    }
}

if (!function_exists('c5_date_format_custom')) {
    /**
     * An Alias of \Concrete\Core\Localization\Service\Date::formatCustom().
     *
     * Render a date/time as a localized string, by specifying a custom format.
     *
     * @param string $format The custom format (see http://www.php.net/manual/en/function.date.php for applicable formats)
     * @param mixed $value The date/time representation (one of the values accepted by toDateTime)
     * @param string $toTimezone The timezone to set. Special values are:<ul>
     *     <li>'system' for the current system timezone</li>
     *     <li>'user' (default) for the user's timezone</li>
     *     <li>'app' for the app's timezone</li>
     *     <li>Other values: one of the PHP supported time zones (see http://us1.php.net/manual/en/timezones.php )</li>
     * </ul>
     * @param string $fromTimezone The original timezone of $value (useful only if $value is a string like '2000-12-31 23:59'); it accepts the same values as $toTimezone
     *
     * @return string Returns an empty string if $value couldn't be parsed, the localized string otherwise
     */
    function c5_date_format_custom($format, $value = 'now', $toTimezone = 'user', $fromTimezone = 'system')
    {
        return c5app('date')->formatCustom($format, $value, $toTimezone, $fromTimezone);
    }
}

if (!function_exists('c5_date_format')) {
    /**
     * An Alias of \Concrete\Core\Localization\Service\Date::formatDate().
     *
     * Render the date part of a date/time as a localized string.
     *
     * @param mixed $value $The date/time representation (one of the values accepted by toDateTime)
     * @param string $format the format name; it can be 'full' (eg 'EEEE, MMMM d, y' - 'Wednesday, August 20, 2014'), 'long' (eg 'MMMM d, y' - 'August 20, 2014'), 'medium' (eg 'MMM d, y' - 'August 20, 2014') or 'short' (eg 'M/d/yy' - '8/20/14'),
     *                      or a skeleton pattern prefixed by '~', e.g. '~yMd'.
     *                      You can also append a caret ('^') or an asterisk ('*') to $width. If so, special day names may be used (like 'Today', 'Yesterday', 'Tomorrow' with '^' and 'today', 'yesterday', 'tomorrow' width '*') instead of the date.
     * @param string $toTimezone The timezone to set. Special values are:<ul>
     *     <li>'system' for the current system timezone</li>
     *     <li>'user' (default) for the user's timezone</li>
     *     <li>'app' for the app's timezone</li>
     *     <li>Other values: one of the PHP supported time zones (see http://us1.php.net/manual/en/timezones.php )</li>
     * </ul>
     *
     * @return string Returns an empty string if $value couldn't be parsed, the localized string otherwise
     */
    function c5_date_format($value = 'now', $format = 'short', $toTimezone = 'user')
    {
        return c5app('date')->formatDate($value, $format, $toTimezone);
    }
}

if (!function_exists('array_key_first')) {
    /**
     * Gets the first key of an array.
     *
     * @param array $array
     *
     * @return mixed
     */
    function array_key_first(array $array)
    {
        return Arr::firstKey($array);
    }
}

if (!function_exists('array_key_last')) {
    /**
     * Gets the last key of an array.
     *
     * @param array $array
     *
     * @return mixed
     */
    function array_key_last(array $array)
    {
        return Arr::lastKey($array);
    }
}

if (!function_exists('in_array_all')) {
    /**
     * Verify that all needles are in haystack array.
     *
     * @param array $needles
     * @param array $haystack
     *
     * @return bool
     */
    function in_array_all(array $needles, array $haystack): bool
    {
        return Arr::inArrayAll($needles, $haystack);
    }
}

if (!function_exists('in_array_any')) {
    /**
     * Verify that at least one of needles is in haystack array.
     *
     * @param array $needles
     * @param array $haystack
     *
     * @return bool
     */
    function in_array_any(array $needles, array $haystack): bool
    {
        return Arr::inArrayAny($needles, $haystack);
    }
}

if (!function_exists('is_countable')) {
    /**
     * Verify that the contents of a variable is a countable value.
     *
     * @param mixed $value
     *
     * @return bool
     */
    function is_countable($value): bool
    {
        return Arr::isCountable($value);
    }
}

if (!function_exists('get_theme_path')) {
    /**
     * @deprecated use theme_path()
     */
    function get_theme_path() { return theme_path(); }
}

if (!function_exists('get_active_locale')) {
    /**
     * @deprecated use current_locale()
     */
    function get_active_locale() { return current_locale(); }
}

if (!function_exists('get_active_language')) {
    /**
     * @deprecated use current_language()
     */
    function get_active_language() { return current_language(); }
}
