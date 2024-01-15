<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.0.0
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

use VanillePlugin\third\AMP;
use PHPUnit\Framework\TestCase;

final class AmpTest extends TestCase
{
    public function testIsActive()
    {
        $this->assertIsBool(AMP::isActive());
    }
}
