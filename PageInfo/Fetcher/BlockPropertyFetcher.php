<?php

namespace Xanweb\Helper\PageInfo\Fetcher;

use Concrete\Core\Page\Page;
use Xanweb\Helper\Page as PageHelper;

class BlockPropertyFetcher extends AbstractPropertyFetcher
{
    /**
     * List of area handles that will be excluded from fetching.
     *
     * @var array|null
     */
    private $excludeAreas;

    /**
     * Function to validate fetched block.
     *
     * @var callable|null
     */
    private $dataValidator;

    /**
     * BlockPropertyFetcher constructor.
     *
     * @param string $btHandle block type handle
     * @param callable $fetchCallback function(BlockController $bcController)
     * @param callable|null $dataValidator myFunction(BlockController $bController): bool
     * @param array|null $excludeAreas List of area handles that will be excluded from fetching
     */
    public function __construct(string $btHandle, callable $fetchCallback, ?callable $dataValidator = null, ?array $excludeAreas = null)
    {
        $this->handle = $btHandle;
        $this->fetchCallback = $fetchCallback;
        $this->excludeAreas = $excludeAreas;
        $this->dataValidator = $dataValidator;
    }

    /**
     * Fetch block from page.
     *
     * @param Page $page
     *
     * @return mixed|null
     */
    public function fetch(Page $page)
    {
        $block = (new PageHelper($page, $this->excludeAreas))->getBlock($this->getHandle(), $this->dataValidator);

        return $block !== null ? ($this->fetchCallback)($block) : null;
    }
}
