<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.3
 * @copyright : (c) 2018 - 2022 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

use VanillePlugin\inc\Arrayify;
use VanillePlugin\inc\Stringify;
use PHPUnit\Framework\TestCase;

final class ArrayifyTest extends TestCase
{
    public function testInArray()
    {
        $array = [0, 1, 'test'];
        $this->assertTrue(Arrayify::inArray('test', $array));
        $this->assertTrue(Arrayify::inArray(0, $array));
        $this->assertTrue(Arrayify::inArray(1, $array));
        $this->assertFalse(Arrayify::inArray('Test', $array));
        $this->assertFalse(Arrayify::inArray('test2', $array));
        $this->assertTrue(!Arrayify::inArray('test3', $array));
        $this->assertFalse(Arrayify::inArray(2, $array));
        $this->assertTrue(!Arrayify::inArray(2, $array));
    }

    public function testMerge()
    {
        $tmp = [];
        $array1 = [0, 1, '2', 3];
        $array2 = [4, 5, '6', 7];
        $this->assertEquals($tmp = Arrayify::merge($tmp,$array1), $array1);
        $this->assertCount(4, $tmp);
        $this->assertEquals($tmp = Arrayify::merge($tmp,$array2), [0, 1, '2', 3, 4, 5, '6', 7]);
        $this->assertCount(8, $tmp);
    }

    public function testPush()
    {
        $tmp = [0, 1];
        $item = Arrayify::push($tmp,'2');
        $this->assertCount($item, $tmp);
        Arrayify::push($tmp,3);
        $this->assertEquals($tmp, [0, 1, '2', 3]);
    }

    public function testCombine()
    {
        $tmp = [];
        $array1 = [0, 1, '2', 3];
        $array2 = [4, 5, '6', 7];
        $tmp = Arrayify::combine($array1,$array2);
        $this->assertArrayHasKey(0, $tmp);
        $this->assertArrayHasKey(1, $tmp);
        $this->assertArrayHasKey('2', $tmp);
        $this->assertArrayHasKey(3, $tmp);
        $this->assertContains(4, $tmp);
        $this->assertContains(5, $tmp);
        $this->assertContains('6', $tmp);
        $this->assertContains(7, $tmp);
    }

    public function testMap()
    {
        $tmp = [];
        $array1 = [1, 2, 3, 4, 5];
        $tmp = Arrayify::map(function($n){
            return ($n * $n * $n);
        },$array1);
        $this->assertEquals([1, 8, 27, 64, 125], $tmp);
    }

    public function testShift()
    {
        $tmp = [];
        $array1 = ['1', '2', '3'];
        $tmp = Arrayify::shift($array1);
        $this->assertEquals('1', $tmp);
        $this->assertEquals(['2', '3'], $array1);
    }

    public function testDiff()
    {
        $tmp = [];
        $array1 = ['1' => 1, '2' => 2, '3' => 3];
        $array2 = ['1' => 1, '2' => 2];
        $tmp = Arrayify::diff($array1,$array2);
        $this->assertEquals(['3' => 3], $tmp);
    }

    public function testHasKey()
    {
        $this->assertTrue(Arrayify::hasKey('1',['1' => 1]));
        $this->assertFalse(Arrayify::hasKey('2',['1' => 1]));
    }

    public function testKeys()
    {
        $array = ['1' => 1, '2' => 2, '3' => 3];
        $this->assertSame([0 => 1, 1 => 2, 2 => 3], Arrayify::keys($array));
    }

    public function testValues()
    {
        $array = ['1' => 1, '2' => 2, '3' => 3];
        $this->assertSame([0 => 1, 1 => 2, 2 => 3], Arrayify::values($array));
    }

    public function testUnique()
    {
        $tmp = [];
        $array1 = ['1', '1', '2', '2', '3'];
        $tmp = Arrayify::unique($array1);
        $this->assertCount(3, $tmp);
        $array2 = Arrayify::unique([1,2,2,'3','3',3,3,4,5,6,6 => '6',6 => '6',7]);
    }

    public function testUniqueMultiple()
    {
        $tmp = [];
        $array1 = [
            [1,2,3,4,5,'6'],
            [1,2,3,4,5,'6'],
            [1,2,3,4,5,'6']
        ];
        $tmp = Arrayify::uniqueMultiple($array1);
        $this->assertCount(3, $array1);
        $this->assertCount(1, $tmp);
    }

    public function testRand()
    {
        $this->assertArrayHasKey(Arrayify::rand([1,2,3,4,5]), [1,2,3,4,5]);
    }

    public function testSlice()
    {
        $this->assertSame([0 => 4], Arrayify::slice([1,2,3,4,5], -2, 1));
    }

    public function testFilter()
    {
        $tmp = [];
        $array1 = [0 => '1', 1 => '2', 2 => '3', 3 => ''];
        $tmp = Arrayify::filter($array1);
        $tmp = Arrayify::values($tmp);
        $this->assertSame(['1', '2', '3'], $tmp);
        $this->assertCount(3, $tmp);
    }

    public function testFormatKeyCase()
    {
        $this->assertSame(['test' => 1], Arrayify::formatKeyCase(['TEST' => 1]));
    }

    public function testSort()
    {
        $default = [['id' => 1],['id' => 3],['id' => 4],['id' => 2]];
        $sorted = Arrayify::sort($default,'id','asc');
        $this->assertSame([['id' => 1],['id' => 2],['id' => 3],['id' => 4]], $sorted);
        $sorted = Arrayify::sort($default,'id','desc');
        $this->assertSame([['id' => 4],['id' => 3],['id' => 2],['id' => 1]], $sorted);
    }

    public function testWalkRecursive()
    {
        $arrays = [['1' => 'test-1'],['2' => 'test-2'],['3' => 'test-3']];
        Arrayify::walkRecursive($arrays, function(&$array) {
            $array = Stringify::replace('test-','tested-',$array);
        });
        $this->assertSame($arrays[0]['1'],'tested-1');
        $this->assertSame($arrays[1]['2'],'tested-2');
        $this->assertSame($arrays[2]['3'],'tested-3');
    }
}
