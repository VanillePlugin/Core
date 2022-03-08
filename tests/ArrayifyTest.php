<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.7.5
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

use VanillePlugin\inc\Arrayify;
use PHPUnit\Framework\TestCase;

final class ArrayifyTest
{
    public function testInArray()
    {
        $array = [0,1,'test'];
        $this->assertEquals(true, Arrayify::inArray('test'));
        $this->assertEquals(false, Arrayify::inArray('test2'));
        $this->assertEquals(true, !Arrayify::inArray('test3'));
        $this->assertEquals(false, Arrayify::inArray(2));
        $this->assertEquals(true, !Arrayify::inArray(2));
    }
}
