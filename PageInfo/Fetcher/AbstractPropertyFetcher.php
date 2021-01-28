<?php

namespace Xanweb\Helper\PageInfo\Fetcher;

use Concrete\Core\Page\Page;

abstract class AbstractPropertyFetcher implements PropertyFetcherInterface
{
    /**
     * @var string
     */
    protected $handle;

    /**
     * @var callable
     */
    protected $fetchCallback;

    /**
     * @return string
     */
    public function getHandle(): string
    {
        return $this->handle;
    }

    public function fetch(Page $page)
    {
        return ($this->fetchCallback)($page);
    }
}
