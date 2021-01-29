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

    /**
     * Factory constructor.
     *
     * @param Config|null $defaultConfig
     */
    public function __construct(?Config $defaultConfig)
    {
        $app = $this->app();
        $this->urlResolver = $app->make(PageUrlResolver::class);
        $this->dh = $app->make('date');
        $this->th = $app->make('helper/text');
        $this->defaultConfig = $defaultConfig ?? ConfigManager::getDefault();
    }

    /**
     * Build PageInfo Fetcher.
     *
     * @param Page $page
     * @param Config|null $config
     *
     * @return PageInfo|null Return PageInfo object or Null if page has COLLECTION_NOT_FOUND Error
     */
    public function build(Page $page, ?Config $config): ?PageInfo
    {
        $pageInfo = null;
        if ($page->getError() !== COLLECTION_NOT_FOUND) {
            $pageInfo = new PageInfo($page, $this->urlResolver, $this->th, $this->dh, $config ?? $this->defaultConfig);
        }

        return $pageInfo;
    }
}
