<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.0.1
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

use VanillePlugin\inc\Image;
use VanillePlugin\inc\File;
use VanillePlugin\inc\Upload;
use VanillePlugin\inc\Stringify;
use VanillePlugin\inc\TypeCheck;
use PHPUnit\Framework\TestCase;

final class ImageTest extends TestCase
{
    public function testImportUrl()
    {
        // First import
        $data = Image::importUrl('https://dummyimage.com/640x360/fff/test1.png');
        $this->assertArrayHasKey('id', $data);
        $this->assertTrue(TypeCheck::isInt($data['id']));
        $this->assertArrayHasKey('url', $data);
        $this->assertTrue(TypeCheck::isString($data['url']));

        // Second import
        $data = Image::importUrl('https://dummyimage.com/640x360/fff/test1.png');
        $this->assertArrayHasKey('id', $data);
        $this->assertTrue(TypeCheck::isInt($data['id']));
        $this->assertArrayHasKey('url', $data);
        $this->assertTrue(TypeCheck::isString($data['url']));
    }

    public function testUpload()
    {
        File::addDir(__DIR__ . '/upload');
        $file = __DIR__ . '/upload/test2.png';

        File::import('https://dummyimage.com/640x360/fff/test2.png', $file);

        $mime = File::getMime($file);
        Upload::set('file', [
            'name'     => File::getFileName($file),
            'tmp_name' => Stringify::formatPath($file),
            'type'     => $mime['type'],
            'size'     => File::getFileSize($file)
        ]);

        $data = Image::upload([
            'test_form' => false,
            'action'    => 'wp_handle_mock_upload' // Test only
        ]);
        $this->assertArrayHasKey('id', $data);
        $this->assertTrue(TypeCheck::isInt($data['id']));
        $this->assertArrayHasKey('url', $data);
        $this->assertTrue(TypeCheck::isString($data['url']));

        File::remove($file);
        File::removeDir(__DIR__ . '/upload');
    }

    public function testValidateMime()
    {
        $this->assertSame(Image::validateMime('image.png'), 'image.png');
    }
}
