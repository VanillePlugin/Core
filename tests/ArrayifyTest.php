<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.7.6
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

use VanillePlugin\inc\Arrayify;
use PHPUnit\Framework\TestCase;

final class ArrayifyTest extends TestCase
{
    public function testInArray()
    {
        $array = [0,1,'test'];
        $this->assertEquals(true, Arrayify::inArray('test',$array));
        $this->assertEquals(true, Arrayify::inArray(0,$array));
        $this->assertEquals(true, Arrayify::inArray(1,$array));
        $this->assertEquals(false, Arrayify::inArray('Test',$array));
        $this->assertEquals(true, Arrayify::inArray('Test',$array,false));
        $this->assertEquals(false, Arrayify::inArray('test2',$array));
        $this->assertEquals(true, !Arrayify::inArray('test3',$array));
        $this->assertEquals(false, Arrayify::inArray(2,$array));
        $this->assertEquals(true, !Arrayify::inArray(2,$array));
    }
}
