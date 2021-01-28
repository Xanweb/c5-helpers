<?php

namespace Xanweb\Helper\PageInfo\Fetcher;

use Concrete\Core\Page\Page;

class AttributePropertyFetcher extends AbstractPropertyFetcher
{
    /**
     * AttributePropertyFetcher constructor.
     *
     * @param string $akHandle attribute key handle
     */
    public function __construct(string $akHandle)
    {
        $this->handle = $akHandle;
        $this->fetchCallback = static function (Page $page) use ($akHandle) {
            return $page->getAttribute($akHandle);
        };
    }
}
