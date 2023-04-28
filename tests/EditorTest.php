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

use VanillePlugin\thirdparty\Editor;
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
