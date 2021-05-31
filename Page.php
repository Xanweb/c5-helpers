<?php

namespace Xanweb\Helper;

use Concrete\Core\Block\Block;
use Concrete\Core\Block\BlockController;
use Concrete\Core\Cache\Level\ExpensiveCache;
use Concrete\Core\Database\Connection\Connection;
use Concrete\Core\Page\Page as PageObject;
use Doctrine\DBAL\Query\QueryBuilder;
use Xanweb\Common\Traits\StaticApplicationTrait;

class Page
{
    use StaticApplicationTrait;

    /**
     * @var Connection
     */
    private static $db;

    /**
     * @var ExpensiveCache
     */
    private static $cache;

    /**
     * @var QueryBuilder
     */
    private static $fetchQuery;

    /**
     * @var PageObject
     */
    private $page;

    /**
     * @var array
     */
    private $includeAreas;

    /**
     * @var array
     */
    private $excludeAreas;

    /**
     * Page constructor.
     *
     * @param PageObject $page
     * @param array|null $excludeAreas List of area handles that will be excluded from fetching.
     * @param array|null $includeAreas List of area handles that will be included in fetching.
     */
    public function __construct(PageObject $page, ?array $excludeAreas = null, array $includeAreas = [])
    {
        $this->page = $page;
        $this->excludeAreas = array_flip(
            array_unique($excludeAreas ?? self::getExcludedAreasConfig())
        );

        $this->includeAreas = ($includeAreas !== []) ? array_flip(array_unique($includeAreas)) : null;
    }

    /**
     * Get block by handle from page.
     * If no data validation is given then first block occurrence will be returned.
     *
     * @param string $btHandle
     * @param callable|null $dataValidator myFunction(BlockController $bController): bool
     *
     * @return BlockController|null
     */
    public function getBlock(string $btHandle, ?callable $dataValidator = null): ?BlockController
    {
        $dataValidator = $dataValidator ?? static function ($bController) { return true; };
        $blockIDs = $this->fetchPageBlocks();

        $block = null;
        foreach ($blockIDs as $row) {
            $_btHandle = $row['btHandle'];
            $_arHandle = $row['arHandle'];
            if ($_btHandle === $btHandle
                && ($this->includeAreas === null || isset($this->includeAreas[$_arHandle]))
                && !isset($this->excludeAreas[$_arHandle])) {
                $b = Block::getByID($row['bID'], $this->page, $_arHandle);
                if ($b !== null && $dataValidator($bController = $b->getController())) {
                    $block = $bController;
                    break;
                }
            }
        }

        return $block;
    }

    /**
     * Get blocks by given handles from page.
     * If no data validation is given then first block occurrences will be returned.
     *
     * @param string[] $btHandles
     * @param callable|null $dataValidator myFunction($bController): bool
     *
     * @return BlockController[] array indexed by block type handle
     */
    public function getBlocks(array $btHandles, ?callable $dataValidator = null): array
    {
        $dataValidator = $dataValidator ?? static function ($bController) { return true; };
        $blockIDs = $this->fetchPageBlocks();

        $blocks = [];
        $btHandles = array_flip(array_unique($btHandles));
        $handlesCount = count($btHandles);

        $i = 0;
        foreach ($blockIDs as $row) {
            $btHandle = $row['btHandle'];
            $arHandle = $row['arHandle'];
            if (!isset($blocks[$btHandle])
                && isset($btHandles[$btHandle])
                && ($this->includeAreas === null || isset($this->includeAreas[$arHandle]))
                && !isset($this->excludeAreas[$arHandle])) {
                $b = Block::getByID($row['bID'], $this->page, $arHandle);

                if ($b !== null && $dataValidator($bController = $b->getController())) {
                    $blocks[$btHandle] = $bController;
                    if ($handlesCount === ++$i) {
                        break;
                    }
                }
            }
        }

        return $blocks;
    }

    final protected static function database(): Connection
    {
        if (!self::$db) {
            self::$db = self::app('database/connection');
        }

        return self::$db;
    }

    final protected static function cache(): ExpensiveCache
    {
        if (!self::$cache) {
            self::$cache = self::app('cache/expensive');
        }

        return self::$cache;
    }

    private function fetchPageBlocks(): array
    {
        $cache = self::cache();
        $item = $cache->getItem(sprintf('xw/page_helper/%s_%s', $this->page->getCollectionID(), $this->page->getVersionID()));
        if (!$item->isMiss()) {
            return (array) $item->get();
        }

        $qb = self::getFetchQuery();
        $qb->setParameters([
            ':cID' => $this->page->getCollectionID(), ':cvID' => $this->page->getVersionID(),
        ]);

        $blocks = $qb->execute()->fetchAll();
        $cache->save($item->set($blocks));

        return $blocks;
    }

    private static function getFetchQuery(): QueryBuilder
    {
        if (!self::$fetchQuery) {
            $db = self::database();

            $qb = $db->createQueryBuilder()->select('b.bID', 'bt.btHandle', 'cvb.arHandle')
                ->from('CollectionVersionBlocks', 'cvb')
                ->innerJoin('cvb', 'Blocks', 'b', 'cvb.bID = b.bID')
                ->innerJoin('b', 'BlockTypes', 'bt', 'b.btID = bt.btID');

            $expr = $qb->expr();
            $qb->where($expr->andX(
                $expr->eq('cvb.cID', ':cID'),
                $expr->orX($expr->eq('cvb.cvID', ':cvID'), $expr->eq('cvb.cbIncludeAll', 1))
            ));

            $qb->orderBy('cvb.cbDisplayOrder');

            self::$fetchQuery = $qb;
        }

        return clone self::$fetchQuery;
    }

    private static function getExcludedAreasConfig(): array
    {
        static $excludedAreasConfig;

        if (!$excludedAreasConfig) {
            $excludedAreasConfig = self::app('config')->get('xanweb.helpers.page_helper.exclude_areas', []);
        }

        return $excludedAreasConfig;
    }
}
