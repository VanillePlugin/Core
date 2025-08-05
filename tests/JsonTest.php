<?php

/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.1.x
 * @copyright : (c) 2018 - 2025 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

use VanillePlugin\inc\Json;
use PHPUnit\Framework\TestCase;

final class JsonTest extends TestCase
{
    public function testEncode()
    {
        $data   = ['test' => "example's", 'test2' => '"example"'];
        $json   = Json::encode($data);
        $result = '{"test":"example\'s","test2":"\"example\""}';
        $this->assertEquals($result, $json);
    }

    public function testDecode()
    {
        $data   = ['test' => "example's", 'test2' => '"example"'];
        $json   = Json::encode($data);
        $result = Json::decode($json, true);
        $this->assertEquals($data, $result);
    }
}
