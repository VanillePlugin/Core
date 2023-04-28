<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.6
 * @copyright : (c) 2018 - 2023 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

use VanillePlugin\thirdparty\Cache;
use PHPUnit\Framework\TestCase;

final class CacheTest extends TestCase
{
    public function testIsActive()
    {
        $this->assertIsBool(Cache::isActive());
    }
}
