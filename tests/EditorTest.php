<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.0.x
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

use VanillePlugin\third\Editor;
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
