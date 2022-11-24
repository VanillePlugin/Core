<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.3
 * @copyright : (c) 2018 - 2022 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

use VanillePlugin\inc\Image;
use VanillePlugin\inc\File;
use VanillePlugin\inc\Upload;
use VanillePlugin\inc\Stringify;
use PHPUnit\Framework\TestCase;

final class ImageTest extends TestCase
{
    public function testImport()
    {
        // First import
        $data = Image::import('https://dummyimage.com/640x360/fff/test1.png');
        $this->assertArrayHasKey('id',$data);
        $this->assertTrue(is_int($data['id']));
        $this->assertArrayHasKey('url',$data);
        $this->assertTrue(is_string($data['url']));

        // Second import
        $data = Image::import('https://dummyimage.com/640x360/fff/test1.png');
        $this->assertArrayHasKey('id',$data);
        $this->assertTrue(is_int($data['id']));
        $this->assertArrayHasKey('url',$data);
        $this->assertTrue(is_string($data['url']));
    }

    public function testUpload()
    {
        File::addDir(__DIR__ . '/upload');
        $file = __DIR__ . '/upload/test2.png';

        File::import('https://dummyimage.com/640x360/fff/test2.png', $file);

        $mime = File::getMime($file);
        Upload::set('file',[
            'name'     => File::getFileName($file),
            'tmp_name' => Stringify::formatPath($file),
            'type'     => $mime['type'],
            'size'     => File::getFileSize($file)
        ]);

        $data = Image::upload([
            'test_form' => false,
            'action'    => 'wp_handle_mock_upload' // Test only
        ]);
        $this->assertArrayHasKey('id',$data);
        $this->assertTrue(is_int($data['id']));
        $this->assertArrayHasKey('url',$data);
        $this->assertTrue(is_string($data['url']));

        File::remove($file);
        File::removeDir(__DIR__ . '/upload');
    }

    public function testValidate()
    {
        $this->assertSame(Image::validate('image.png'),'image.png');
    }

    public function testGetAllowedMimes()
    {
        $this->assertTrue(is_array(Image::getAllowedMimes()));
    }
}
