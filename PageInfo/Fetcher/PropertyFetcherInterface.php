<?php

namespace Xanweb\Helper\PageInfo\Fetcher;

use Concrete\Core\Page\Page;

interface PropertyFetcherInterface
{
    /**
     * Get property handle.
     *
     * @return string
     */
    public function getHandle(): string;

    /**
     * Fetch Property.
     *
     * @param Page $page
     *
     * @return mixed
     */
    public function fetch(Page $page);
}
