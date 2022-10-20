<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.0
 * @copyright : (c) 2018 - 2022 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 */

use VanillePlugin\inc\Filesystem;
use PHPUnit\Framework\TestCase;

final class FilesystemTest extends TestCase
{
    public function testCreate()
    {
        $dirs = [
            __DIR__ . '/temp1',
            __DIR__ . '/temp2',
            __DIR__ . '/temp3'
        ];
        Filesystem::create($dirs);
        $this->assertTrue(Filesystem::isDir(__DIR__ . '/temp1'));
        $this->assertTrue(Filesystem::isDir(__DIR__ . '/temp2'));
        $this->assertTrue(Filesystem::isDir(__DIR__ . '/temp3'));
        Filesystem::removeDir(__DIR__ . '/temp1');
        Filesystem::removeDir(__DIR__ . '/temp2');
        Filesystem::removeDir(__DIR__ . '/temp3');
    }
}
