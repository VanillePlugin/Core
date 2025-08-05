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

use VanilleThird\Editor;
use PHPUnit\Framework\TestCase;

final class EditorTest extends TestCase
{
    public function testIsClassic()
    {
        $this->assertIsBool(Editor::isClassic());
    }

    public function testIsGutenberg()
    {
        $this->assertIsBool(Editor::isGutenberg());
    }
}
