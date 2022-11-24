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

use VanillePlugin\inc\Converter;
use PHPUnit\Framework\TestCase;

final class ConverterTest extends TestCase
{
    public function testToObject()
    {
        $this->assertTrue(is_object(
            Converter::toObject(['test' => '12345'])
        ));
    }

    public function testToArray()
    {
        $object = new \stdClass();
        $object->test = '12345';
        $this->assertTrue(is_array(
            Converter::toArray($object)
        ));
    }

    public function testToMoney()
    {
        $this->assertTrue(is_float(
            Converter::toMoney('15.1')
        ));
    }
}
