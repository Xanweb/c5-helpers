<?php

namespace Xanweb\Helper\PageInfo;

use Concrete\Core\Page\Page;
use Concrete\Core\Url\Resolver\PageUrlResolver;
use Xanweb\Common\Traits\ApplicationTrait;

class Factory
{
    use ApplicationTrait;

    /**
     * @var PageUrlResolver
     */
    private $urlResolver;

    /**
     * @var \Concrete\Core\Localization\Service\Date
     */
    private $dh;

    /**
     * @var \Concrete\Core\Utility\Service\Text
     */
    private $th;

    /**
     * @var Config
     */
    private $defaultConfig;

    public function __construct(Config $defaultConfig, PageUrlResolver $urlResolver)
    {
        $this->urlResolver = $urlResolver;
        $this->dh = $this->app('date');
        $this->th = $this->app('helper/text');
        $this->defaultConfig = $defaultConfig;
    }

    /**
     * Build PageInfo Fetcher.
     *
     * @param Page $page
     * @param Config|null $config
     *
     * @return PageInfo|null Return PageInfo object or Null if page has COLLECTION_NOT_FOUND Error
     */
    public function build(Page $page, Config $config = null): ?PageInfo
    {
        $pageInfo = null;
        if ($page->getError() !== COLLECTION_NOT_FOUND) {
            $pageInfo = new PageInfo($page, $this->urlResolver, $this->th, $this->dh, $config ?? $this->defaultConfig);
        }

        return $pageInfo;
    }
}
