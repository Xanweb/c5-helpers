<?php
namespace Xanweb\Helper;

use Concrete\Core\Block\Block;
use Concrete\Core\Page\Page as PageObject;
use Concrete\Core\Support\Facade\Application;

class Page
{
    /**
     * @var PageObject
     */
    private $page;

    /**
     * @var \Concrete\Core\Cache\Level\RequestCache
     */
    private $cache;

    /**
     * @var array
     */
    private $excludeAreas;

    public function __construct(PageObject $page, array $excludeAreas = null)
    {
        $this->page = $page;

        $app = Application::getFacadeApplication();
        $this->cache = $app['cache/request'];
        $this->excludeAreas = $excludeAreas ?: $app['config']->get('xanweb.helpers.page_helper.exclude_areas', []);
    }

    /**
     * @param string $btHandle
     *
     * @return \Concrete\Core\Block\BlockController
     */
    public function getBlock($btHandle)
    {
        $block = null;
        $cachedItem = $this->cache->getItem("page/{$this->page->getCollectionID()}/block/{$btHandle}");
        if ($cachedItem->isMiss()) {
            $blockIDs = $this->page->getBlockIDs();
            if (is_array($blockIDs)) {
                foreach ($blockIDs as $row) {
                    if (in_array($row['arHandle'], $this->excludeAreas)) {
                        continue;
                    }

                    $ab = Block::getByID($row['bID'], $this->page, $row['arHandle']);
                    if (is_object($ab) && $ab->getBlockTypeHandle() == $btHandle) {
                        $block = $ab->getController();
                        break;
                    }
                }
            }

            $this->cache->save($cachedItem->set($block));
        } else {
            $block = $cachedItem->get();
        }

        return $block;
    }

    /**
     * @param string[] $btHandles
     *
     * @return array
     */
    public function getBlocks(array $btHandles)
    {
        $blockIDs = $this->page->getBlockIDs();
        $handlesCount = count($btHandles);
        $blocks = [];

        $i = 0;
        foreach ($blockIDs as $row) {
            $ab = Block::getByID($row['bID'], $this->page, $row['arHandle']);
            if (is_object($ab) && !isset($blocks[$ab->getBlockTypeHandle()]) && in_array($ab->getBlockTypeHandle(), $btHandles)) {
                $blocks[$ab->getBlockTypeHandle()] = $ab->getController();
                ++$i;
                if ($handlesCount == $i) {
                    break;
                }
            }
        }

        return $blocks;
    }
}
