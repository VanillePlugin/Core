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

use VanilleThird\Cache;
use PHPUnit\Framework\TestCase;

final class CacheTest extends TestCase
{
    public function testIsActive()
    {
        $this->assertIsBool(Cache::isActive());
    }
}
