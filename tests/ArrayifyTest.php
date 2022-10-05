<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.7.9
 * @copyright : (c) 2018 - 2022 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 */

use VanillePlugin\inc\Arrayify;
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
        $this->assertTrue(Arrayify::inArray('Test', $array, false));
        $this->assertFalse(Arrayify::inArray('test2', $array));
        $this->assertTrue(!Arrayify::inArray('test3', $array));
        $this->assertFalse(Arrayify::inArray(2, $array));
        $this->assertTrue(!Arrayify::inArray(2, $array));
        $this->assertTrue(Arrayify::inArray('1', $array, false));
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

    public function testUnique()
    {
        $tmp = [];
        $array1 = ['1', '1', '2', '2', '3'];
        $tmp = Arrayify::unique($array1);
        $this->assertCount(3, $tmp);
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

    public function testOrder()
    {
        $tmp = [];
        $array1 = [1,3,4,5,2];
        $tmp = Arrayify::order($array1);
        $tmp = Arrayify::values($tmp);
        $this->assertSame([1,2,3,4,5], $tmp);
    }
}
