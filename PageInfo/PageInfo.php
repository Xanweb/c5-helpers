<?php

namespace Xanweb\Helper\PageInfo;

use Concrete\Core\Entity\File\File;
use Concrete\Core\Localization\Service\Date;
use Concrete\Core\Page\Page;
use Concrete\Core\Url\Resolver\PageUrlResolver;
use Concrete\Core\Utility\Service\Text;
use League\URL\URLInterface;

class PageInfo
{
    /**
     * @var Page
     */
    private $page;

    /**
     * @var PageUrlResolver
     */
    private $urlResolver;

    /**
     * @var Text
     */
    private $th;

    /**
     * @var Date
     */
    private $dh;

    /**
     * @var Config
     */
    private $config;

    public function __construct(Page $page, PageUrlResolver $urlResolver, Text $th, Date $dh, Config $config)
    {
        $this->page = $page;
        $this->th = $th;
        $this->dh = $dh;
        $this->urlResolver = $urlResolver;
        $this->config = $config;
    }

    /**
     * Get Page Name after applying htmlentities().
     *
     * @return string
     */
    public function fetchPageName(): string
    {
        $pageName = '';
        foreach ($this->config->getPageNameFetchers() as $fetcher) {
            $pageName = $fetcher->fetch($this->page);
            if (!empty($pageName)) {
                break;
            }
        }

        return $this->th->entities($pageName);
    }

    /**
     * Get Page Description.
     *
     * @param int|null $truncateChars
     *
     * @return string
     */
    public function fetchPageDescription(?int $truncateChars = null): string
    {
        $description = '';
        foreach ($this->config->getPageDescriptionFetchers() as $fetcher) {
            $description = $fetcher->fetch($this->page);
            if (!empty($description)) {
                break;
            }
        }

        return $truncateChars ? $this->th->shortenTextWord($description, $truncateChars) : $description;
    }

    /**
     * Get Page URL.
     *
     * @return URLInterface
     */
    public function getURL(): ?URLInterface
    {
        return $this->urlResolver->resolve([$this->page]);
    }

    /**
     * Get Navigation Target.
     *
     * @return string
     */
    public function getTarget(): string
    {
        $akNavTarget = $this->config->getNavTargetAttributeKey();
        $target = ($this->page->getCollectionPointerExternalLink() !== '' && $this->page->openCollectionPointerExternalLinkInNewWindow()) ? '_blank' : $this->page->getAttribute($akNavTarget);

        return empty($target) ? '_self' : $target;
    }

    /**
     * Get Publish Date.
     *
     * @param string|null $format The custom format (see http://www.php.net/manual/en/function.date.php for applicable formats)
     *
     * @return string
     * @noinspection PhpDocMissingThrowsInspection
     */
    public function getPublishDate(?string $format = null): string
    {
        $datePublic = $this->page->getCollectionDatePublic();
        /** @noinspection PhpUnhandledExceptionInspection */
        return $format ? $this->dh->formatCustom($format, $datePublic) : $this->dh->formatDate($datePublic);
    }

    /**
     * Get Main Page Thumbnail.
     *
     * @return File|null
     */
    public function fetchThumbnail(): ?File
    {
        $thumbnail = null;
        foreach ($this->config->getThumbnailFetchers() as $fetcher) {
            $thumbnail = $fetcher->fetch($this->page);
            if ($thumbnail !== null) {
                break;
            }
        }

        return $thumbnail;
    }
}
