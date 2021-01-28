<?php

namespace Xanweb\Helper\PageInfo;

use Concrete\Core\Attribute\Category\PageCategory;
use Concrete\Core\Entity\Attribute\Key\Key as AttributeKey;
use Xanweb\Helper\PageInfo\Fetcher\PropertyFetcherInterface;

class Config
{
    /**
     * @var PageCategory
     */
    private $akc;

    /**
     * @var AttributeKey
     */
    private $akNavTarget;

    /**
     * @return PropertyFetcherInterface[]
     */
    private $pageNameFetchers = [];

    /**
     * @return PropertyFetcherInterface[]
     */
    private $pageDescriptionFetchers = [];

    /**
     * @return PropertyFetcherInterface[]
     */
    private $thumbnailFetchers = [];

    public function __construct(PageCategory $akc)
    {
        $this->akc = $akc;
        $this->setNavTargetAttributeKey('nav_target');
    }

    /**
     * Get Nav Target Attribute Key.
     *
     * @return AttributeKey|null
     */
    public function getNavTargetAttributeKey(): ?AttributeKey
    {
        return $this->akNavTarget;
    }

    /**
     * Set Nav Target Attribute Key.
     *
     * @param string $akHandle
     */
    public function setNavTargetAttributeKey(string $akHandle): void
    {
        $this->akNavTarget = $this->akc->getAttributeKeyByHandle($akHandle);
    }

    /**
     * Register Page Name Fetcher (Call Order is important).
     *
     * @param PropertyFetcherInterface $fetcher
     */
    public function registerPageNameFetcher(PropertyFetcherInterface $fetcher): void
    {
        $this->pageNameFetchers[] = $fetcher;
    }

    /**
     * Register Page Description Fetcher (Call Order is important).
     *
     * @param PropertyFetcherInterface $fetcher
     */
    public function registerPageDescriptionFetcher(PropertyFetcherInterface $fetcher): void
    {
        $this->pageDescriptionFetchers[] = $fetcher;
    }

    /**
     * Register Thumbnail Fetcher (Call Order is important).
     *
     * @param PropertyFetcherInterface $fetcher
     */
    public function registerThumbnailFetcher(PropertyFetcherInterface $fetcher): void
    {
        $this->thumbnailFetchers[] = $fetcher;
    }

    /**
     * @return PropertyFetcherInterface[]
     */
    public function getPageDescriptionFetchers(): array
    {
        return $this->pageDescriptionFetchers;
    }

    /**
     * @return PropertyFetcherInterface[]
     */
    public function getPageNameFetchers(): array
    {
        return $this->pageNameFetchers;
    }

    /**
     * @return PropertyFetcherInterface[]
     */
    public function getThumbnailFetchers(): array
    {
        return $this->thumbnailFetchers;
    }
}
