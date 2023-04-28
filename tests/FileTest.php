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

use VanillePlugin\inc\File;
use VanillePlugin\inc\Image;
use VanillePlugin\inc\Arrayify;
use VanillePlugin\inc\TypeCheck;
use PHPUnit\Framework\TestCase;

final class FileTest extends TestCase
{
    public function testGetParentDir()
    {
        $this->assertSame(basename(File::getParentDir(__FILE__)),'tests');
    }

    public function testGetExtension()
    {
        $this->assertSame(File::getExtension(__FILE__),'php');
    }

    public function testGetName()
    {
        $this->assertSame(File::getName(__FILE__),'FileTest');
    }

    public function testGetFileName()
    {
        $this->assertSame(File::getFileName(__FILE__),'FileTest.php');
    }

    public function testGetLastAccess()
    {
        $this->assertTrue(File::getLastAccess(__FILE__) > 0);
    }

    public function testGetLastChange()
    {
        $this->assertTrue(File::getLastChange(__FILE__) > 0);
    }

    public function testGetFileSize()
    {
        $this->assertTrue(File::getFileSize(__FILE__) > 0);
    }

    public function testGetSize()
    {
        $this->assertTrue(!empty(File::getSize(__FILE__)));
    }

    public function testGetPermissions()
    {
        $this->assertSame(File::getPermissions(__FILE__),'0666');
    }

    public function testW()
    {
        File::w(__DIR__ . '/temp');
        $this->assertTrue(File::exists(__DIR__ . '/temp'));
        File::remove(__DIR__ . '/temp');
    }

    public function testR()
    {
        File::w(__DIR__ . '/temp');
        File::addString(__DIR__ . '/temp','test');
        $this->assertSame(File::r(__DIR__ . '/temp'),'test');
    }

    public function testAddString()
    {
        File::w(__DIR__ . '/temp');
        File::addString(__DIR__ . '/temp','test');
        $this->assertSame(File::r(__DIR__ . '/temp'),'test');
        File::addString(__DIR__ . '/temp',' 123');
        $this->assertSame(File::r(__DIR__ . '/temp'),'test 123');
        File::remove(__DIR__ . '/temp');
    }

    public function testAddBreak()
    {
        File::w(__DIR__ . '/temp');
        File::addBreak(__DIR__ . '/temp',' 123');
        $this->assertSame(File::r(__DIR__ . '/temp'),PHP_EOL);
        File::remove(__DIR__ . '/temp');
    }

    public function testRemove()
    {
        File::w(__DIR__ . '/temp');
        $this->assertTrue(File::remove(__DIR__ . '/temp'));
    }

    public function testCopy()
    {
        File::w(__DIR__ . '/temp');
        File::copy(__DIR__ . '/temp',__DIR__ . '/temp2');
        $this->assertTrue(File::exists(__DIR__ . '/temp'));
        File::remove(__DIR__ . '/temp');
        File::remove(__DIR__ . '/temp2');
    }

    public function testMove()
    {
        File::w(__DIR__ . '/temp');
        File::move(__DIR__ . '/temp',__DIR__ . '/temp2');
        $this->assertTrue(File::exists(__DIR__ . '/temp2'));
        File::remove(__DIR__ . '/temp2');
    }

    public function testIsFile()
    {
        File::w(__DIR__ . '/temp');
        $this->assertTrue(File::isFile(__DIR__ . '/temp'));
        File::remove(__DIR__ . '/temp');
        File::addDir(__DIR__ . '/temp');
        $this->assertFalse(File::isFile(__DIR__ . '/temp'));
        File::removeDir(__DIR__ . '/temp');
    }

    public function testIsEmpty()
    {
        File::w(__DIR__ . '/temp');
        $this->assertTrue(File::isEmpty(__DIR__ . '/temp'));
        File::addString(__DIR__ . '/temp',1);
        $this->assertFalse(File::isEmpty(__DIR__ . '/temp'));
        File::remove(__DIR__ . '/temp');
    }

    public function testIsReadable()
    {
        $this->assertTrue(File::isReadable(__FILE__));
    }

    public function testIsWritable()
    {
        $this->assertTrue(File::isWritable(__FILE__));
    }

    public function testAddDir()
    {
        File::addDir(__DIR__ . '/temp');
        $this->assertTrue(File::isDir(__DIR__ . '/temp'));
        File::removeDir(__DIR__ . '/temp');
    }

    public function testRemoveDir()
    {
        File::addDir(__DIR__ . '/temp');
        $this->assertTrue(File::removeDir(__DIR__ . '/temp'));
    }

    public function testClearDir()
    {
        File::addDir(__DIR__ . '/temp');
        File::w(__DIR__ . '/temp/temp2');
        $this->assertTrue(File::exists(__DIR__ . '/temp/temp2'));
        File::clearDir(__DIR__ . '/temp');
        $this->assertFalse(File::exists(__DIR__ . '/temp/temp2'));
        File::removeDir(__DIR__ . '/temp');
    }

    public function testExists()
    {
        File::w(__DIR__ . '/temp');
        $this->assertTrue(File::exists(__DIR__ . '/temp'));
        $this->assertFalse(File::exists(__DIR__ . '/temp2'));
        File::remove(__DIR__ . '/temp');
    }

    public function testScanDir()
    {
        $this->assertTrue(
            Arrayify::inArray('FileTest.php',File::scanDir(__DIR__))
        );
    }

    public function testIndex()
    {
        $this->assertTrue(
            TypeCheck::isArray(File::index(__DIR__))
        );
    }

    public function testLast()
    {
        $this->assertTrue(
            TypeCheck::isString(File::last(__DIR__))
        );
    }

    public function testFirst()
    {
        $this->assertTrue(
            TypeCheck::isString(File::first(__DIR__))
        );
    }

    public function testCount()
    {
        $this->assertTrue(
            TypeCheck::isInt(File::count(__DIR__))
        );
    }

    public function testParseIni()
    {
        File::w(__DIR__ . '/temp');
        File::addString(__DIR__ . '/temp','test = 123;');
        $this->assertEquals(File::parseIni(__DIR__ . '/temp'), [
            'test' => '123'
        ]);
        File::remove(__DIR__ . '/temp');
    }

    public function testImport()
    {
        $this->assertTrue(File::import('https://example.com/',__DIR__ . '/temp'));
        File::remove(__DIR__ . '/temp');
    }

    public function testDownload()
    {
        File::w(__DIR__ . '/temp');
        $this->assertFalse(File::download(__DIR__ . '/temp'));
        File::remove(__DIR__ . '/temp');
    }

    public function testValidate()
    {
        $this->assertSame(
            File::validate('image.png',Image::getAllowedMimes()),
            'image.png'
        );
    }

    public function testGetMime()
    {
        $this->assertEquals(File::getMime('temp.txt'), [
            'ext'  => 'txt',
            'type' => 'text/plain'
        ]);
    }

    public function testGetAllowedMimes()
    {
        $this->assertTrue(is_array(File::getAllowedMimes()));
    }

    public function testAnalyse()
    {
        $this->assertTrue(
            TypeCheck::isArray(File::analyse(__FILE__))
        );
    }
}
