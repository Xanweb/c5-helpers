<?php
namespace Xanweb\Helper;

use Mockery as m;
use PHPUnit\Framework\TestCase;
use Xanweb\Helper\Page as PageHelper;

class PageWithCacheDisabledTest extends TestCase
{
    protected static $appMock;

    public static function setUpBeforeClass(): void
    {
        $config = m::mock('config');
        $config->shouldReceive('get')
            ->with('xanweb.helpers.page_helper.exclude_areas', [])
            ->andReturn([]);

        $cacheItemMock = m::mock('cacheItem');
        $cacheItemMock->shouldReceive('isMiss')
                      ->andReturn(TRUE);
        $cacheItemMock->shouldIgnoreMissing();

        $cacheMock = m::mock('cache');
        $cacheMock->shouldIgnoreMissing();
        $cacheMock->shouldReceive('getItem')
        ->andReturn($cacheItemMock);

        self::$appMock = m::mock('Concrete\Core\Application\Application', 'ArrayAccess');
        self::$appMock->shouldReceive('offsetGet')
            ->with('config')
            ->andReturn($config);
        self::$appMock->shouldReceive('make')
            ->with('cache/request')
            ->andReturn($cacheMock);
    }

    protected function setUp(): void
    {
        m::mock('overload:Concrete\Core\Support\Facade\Application')
            ->shouldReceive('getFacadeApplication')
            ->andReturn(self::$appMock);
    }

    /**
     * @return array
     */
    public function provideDataBlock()
    {
        return [
            [
                [
                    'blockIDs' => [
                        ['bID' => 1001, 'btHandle' => 'page_heading', 'arHandle' => 'Main'],
                        ['bID' => 1004, 'btHandle' => 'content', 'arHandle' => 'Main'],
                        ['bID' => 1002, 'btHandle' => 'image', 'arHandle' => 'Sidebar'],
                        ['bID' => 1003, 'btHandle' => 'xan_image', 'arHandle' => 'Main'],
                        ['bID' => 1005, 'btHandle' => 'image', 'arHandle' => 'Main'],
                    ],
                    'btHandle' => 'image',
                    'pageID' => 1,
                    'ignoredAreas' => NULL
                ],
                1002
            ],
            [
                [
                    'blockIDs' => [
                        ['bID' => 1001, 'btHandle' => 'page_heading', 'arHandle' => 'Main'],
                        ['bID' => 1004, 'btHandle' => 'content', 'arHandle' => 'Main'],
                        ['bID' => 1002, 'btHandle' => 'image', 'arHandle' => 'Sidebar'],
                        ['bID' => 1003, 'btHandle' => 'xan_image', 'arHandle' => 'Main'],
                        ['bID' => 1005, 'btHandle' => 'image', 'arHandle' => 'Main'],
                    ],
                    'btHandle' => 'image',
                    'pageID' => 1,
                    'ignoredAreas' => ['Sidebar']
                ],
                1005
            ],
            [
                [
                    'blockIDs' => [
                        ['bID' => 1001, 'btHandle' => 'page_heading', 'arHandle' => 'Main'],
                        ['bID' => 1004, 'btHandle' => 'content', 'arHandle' => 'Main'],
                        ['bID' => 1002, 'btHandle' => 'image', 'arHandle' => 'Sidebar'],
                        ['bID' => 1003, 'btHandle' => 'xan_image', 'arHandle' => 'Main'],
                        ['bID' => 1005, 'btHandle' => 'image', 'arHandle' => 'Main'],
                    ],
                    'btHandle' => 'page_heading',
                    'pageID' => 2,
                    'ignoredAreas' => ['Sidebar']
                ],
                1001
            ],
        ];
    }

    /**
     * @dataProvider provideDataBlock
     */
    public function testGetBlock($data, $expected)
    {
        $page = m::mock('Concrete\Core\Page\Page');
        $page->shouldReceive('getCollectionID')
            ->andReturn($data['pageID']);

        $page->shouldReceive('getBlockIDs')
            ->andReturn($data['blockIDs']);

        $blockOverlap = m::mock('overload:Concrete\Core\Block\Block');

        foreach ($data['blockIDs'] as $row) {
            $blockMock = m::mock('block');

            $blockControllerMock = m::mock('blockController');
            $blockControllerMock
                ->shouldReceive('getBlockObject')
                ->andReturn($blockMock);

            $blockMock
                ->shouldReceive('getBlockID')
                ->andReturn($row['bID']);
            $blockMock
                ->shouldReceive('getBlockTypeHandle')
                ->andReturn($row['btHandle']);
            $blockMock
                ->shouldReceive('getController')
                ->andReturn($blockControllerMock);

            $blockOverlap->shouldReceive('getByID')
                ->with($row['bID'], $page, $row['arHandle'])
                ->andReturn($blockMock);
        }

        $pageHelper = new PageHelper($page, $data['ignoredAreas']);
        $blockController = $pageHelper->getBlock($data['btHandle']);

        $this->assertIsObject($blockController);
        $this->assertEquals($expected, $blockController->getBlockObject()->getBlockID());
    }

    /**
     * @return array
     */
    public function provideDataBlockNotFound()
    {
        return [
            [
                [
                    'blockIDs' => NULL,
                    'btHandle' => 'image',
                    'pageID' => 1,
                    'ignoredAreas' => NULL
                ]
            ],
            [
                [
                    'blockIDs' => [],
                    'btHandle' => 'image',
                    'pageID' => 1,
                    'ignoredAreas' => []
                ]
            ],
            [
                [
                    'blockIDs' => [
                        ['bID' => 1001, 'btHandle' => 'page_heading', 'arHandle' => 'Header'],
                        ['bID' => 1004, 'btHandle' => 'content', 'arHandle' => 'Main'],
                        ['bID' => 1002, 'btHandle' => 'image', 'arHandle' => 'Sidebar'],
                        ['bID' => 1003, 'btHandle' => 'xan_image', 'arHandle' => 'Main'],
                    ],
                    'btHandle' => 'image',
                    'pageID' => 1,
                    'ignoredAreas' => ['Header', 'Sidebar']
                ]
            ],
            [
                [
                    'blockIDs' => [
                        ['bID' => 1001, 'btHandle' => 'page_heading', 'arHandle' => 'Header'],
                        ['bID' => 1001, 'btHandle' => 'content', 'arHandle' => 'Main'],
                        ['bID' => 1002, 'btHandle' => 'page_list', 'arHandle' => 'Sidebar'],
                    ],
                    'btHandle' => 'image',
                    'pageID' => 2,
                    'ignoredAreas' => []
                ]
            ],
        ];
    }

    /**
     * @dataProvider provideDataBlockNotFound
     */
    public function testGetBlockNotFound($data)
    {
        $page = m::mock('Concrete\Core\Page\Page');
        $page->shouldReceive('getCollectionID')
            ->andReturn($data['pageID']);

        $page->shouldReceive('getBlockIDs')
            ->andReturn($data['blockIDs']);

        $blockOverlap = m::mock('overload:Concrete\Core\Block\Block');

        if (is_array($data['blockIDs'])) {
            foreach ($data['blockIDs'] as $row) {
                $blockMock = m::mock('block');
                $blockMock
                    ->shouldReceive('getBlockTypeHandle')
                    ->andReturn($row['btHandle']);

                $blockOverlap->shouldReceive('getByID')
                    ->with($row['bID'], $page, $row['arHandle'])
                    ->andReturn($blockMock);
            }
        }

        $pageHelper = new PageHelper($page, $data['ignoredAreas']);
        $this->assertNull($pageHelper->getBlock($data['btHandle']));
    }

    protected function tearDown(): void
    {
        m::close();
    }
}
