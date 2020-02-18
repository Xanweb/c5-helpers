<?php
namespace Xanweb\Helper\Test;

use Xanweb\Helper\Arr;
use PHPUnit\Framework\TestCase;

class ArrTest extends TestCase
{

    /**
     * @return array
     */
    public function provideFirstKey()
    {
        return [
            [[1, 2, 3], 0],
            [[1 => 'Item 1', 2 => 'Item 2'], 1],
            [['item1' => 'Item 1', 'item2' => 'Item 2'], 'item1'],
            [[], NULL],
        ];
    }

    /**
     * @dataProvider provideFirstKey
     */
    public function testFirstKey($array, $expected)
    {
        $this->assertSame($expected, Arr::firstKey($array));
    }

    /**
     * @return array
     */
    public function provideLastKey()
    {
        return [
            [[1, 2, 3], 2],
            [[1 => 'Item 1', 2 => 'Item 2'], 2],
            [['item1' => 'Item 1', 'item2' => 'Item 2'], 'item2'],
            [[], NULL],
        ];
    }

    /**
     * @dataProvider provideLastKey
     */
    public function testLastKey($array, $expected)
    {
        $this->assertSame($expected, Arr::lastKey($array));
    }
}
