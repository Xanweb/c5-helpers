<?php

namespace Xanweb\Helper\PageInfo\Fetcher;

use Concrete\Core\Page\Page;
use Xanweb\Helper\PageInfo\Fetcher\Exception\UnsupportedPropertyException;

class PagePropertyFetcher extends AbstractPropertyFetcher
{
    public const PAGE_NAME = 'page_name';
    public const PAGE_DESCRIPTION = 'page_description';

    /**
     * PagePropertyFetcher constructor.
     *
     * @param string $handle PAGE_NAME or PAGE_DESCRIPTION
     *
     * @throws UnsupportedPropertyException
     */
    public function __construct(string $handle)
    {
        $this->handle = $handle;
        switch ($handle) {
            case self::PAGE_NAME:
                $this->fetchCallback = static function (Page $page) {
                    return $page->getCollectionName();
                };

                break;
            case self::PAGE_DESCRIPTION:
                $this->fetchCallback = static function (Page $page) {
                    return $page->getCollectionDescription();
                };
                break;
            default:
                throw new UnsupportedPropertyException(t('Can\'t get `%s` from page.', $handle));
        }
    }
}
