# Concrete5 Helpers Suite
[![Latest Version on Packagist](https://img.shields.io/packagist/v/xanweb/c5-helpers.svg?maxAge=2592000&style=flat-square)](https://packagist.org/packages/xanweb/c5-helpers)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

Collection of useful helpers for Concrete5

- [`array_key_first`](https://php.net/array_key_first) (From PHP 7.3 core)
- [`array_key_last`](https://php.net/array_key_last) (From PHP 7.3 core)
- [`is_countable`](https://php.net/is_countable) (From PHP 7.3 core)
- `in_array_all` Verify that all needles are in haystack array.
- `in_array_any` Verify that at least one of needles is in haystack array.
- `strip_spaces` Remove all spaces from the given string.
- `current_locale` Get Current Page Locale.
- `current_language` Get Current Page Language.
- `active_locale` An Alias of \Localization::activeLocale().
- `active_language` An Alias of \Localization::activeLanguage().
- `theme_path` Get Site Theme Path  
- `c5_date_format` An Alias of \Concrete\Core\Localization\Service\Date::formatDate().
- `c5_date_format_custom` An Alias of \Concrete\Core\Localization\Service\Date::formatCustom().
- `Xanweb\Helper\Page::getBlock` and `Xanweb\Helper\Page::getBlocks` for fetching block(s) from page

## Installation

Include library to your composer.json
```bash
composer require xanweb/c5-helpers
```

#### Usage of Xanweb\Helper\Page

```php 
    use Xanweb\Helper\Page as PageHelper;

    $ph = new PageHelper(
        $page, // Page Object
        ['Header', 'Footer'], // Optional argument to exclude some areas from fetching
        ['Main'] // Optional argument to include some areas in fetching
    );
    
    // Get the first valid instance of required block
    $contentBlockController = $ph->getBlock(
        'content', // Block Type Handle 
        function (BlockController $bController) { // Optional callable to test for valid block
            return !empty($bController->getContent());
        }
    );

    // Get the first valid instances of required blocks
    $blocksControllers = $ph->getBlocks(
        ['image', 'content'], // Block Types Handle 
        function (BlockController $bController) { // Optional callable to test for valid block
            if ($bController instanceof \Concrete\Block\Image\Controller) {
                return is_object($this->getFileObject());
            }

            if ($bController instanceof \Concrete\Block\Content\Controller) {
                return !empty($bController->getContent());
            }

            return false;
        }
    );

    /**
     *  - $blocksControllers array is indexed by btHandle: ['image' => $bController, 'content' => $bController]
     *  - If no block is found $blocksControllers will be an empty array 
     */  
```
