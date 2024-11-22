<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.1.x
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\inc;

final class Image extends File
{
	/**
	 * Import image from URL.
	 *
	 * @access public
	 * @param string $url
	 * @param bool $override
	 * @return mixed
	 */
	public static function importUrl(string $url, bool $override = true)
	{
		// Check valid image
		if ( !($name = self::isValidMime($url)) ) {
			return false;
		}

		// Set image upload data
		$data = self::getMimeType($name);
		$mime = $data['type'];
		$dir  = Globals::upload();
		$path = "{$dir['path']}/{$name}"; // Keep non formatted path

		// Get existing image from gallery by filename (Title)
		if ( ($id = Attachment::getIdByTitle(self::getName($path))) ) {

		    return [
		    	'id'  => $id,
		    	'url' => Attachment::getUrlById($id)
		    ];

		} else {

			// Duplicate image
			if ( !$override && self::exists($path) ) {
				$ext  = $data['ext'];
				$tmp  = self::getName($name);
				$id   = Tokenizer::getUniqueId();
				$name = "{$tmp}-{$id}.{$ext}";
				$path = "{$dir['path']}/{$name}";
			}

			// Import image
			if ( !self::import($url, $path) ) {
				return false;
			}

			// Insert image attachment
			return Attachment::insert($path, [
				'url'  => "{$dir['url']}/{$name}",
				'type' => $mime
			]);
		}
	}

	/**
	 * Upload image using post.
	 *
	 * @access public
	 * @param array $args
	 * @return mixed
	 */
	public static function upload(array $args = [])
	{
		// Get file from global
		$file = Upload::isSetted('file') 
		? (array)Upload::get('file') : [];

		// Check valid image mime
		if ( !self::isValidMime($file['name']) ) {
			return false;
		}

		// Set image upload data
		$data = Upload::handle($file, $args);

		// Insert image attachment
		return Attachment::insert($data['file'], $data);
	}

	/**
	 * Validate image mime.
	 *
	 * @access public
	 * @param string $file
	 * @return bool
	 */
	public static function isValidMime(string $file) : bool
	{
		return Validator::isValidMime($file, [
			'jpg'  => 'image/jpeg',
			'jpeg' => 'image/jpeg',
			'bmp'  => 'image/bmp',
			'png'  => 'image/png',
			'gif'  => 'image/gif'
		]);
	}

	/**
	 * Add image size.
	 *
	 * @access public
	 * @param string $name
	 * @param int $width
	 * @param int $height
	 * @param mixed $crop
	 * @return mixed
	 */
	public static function addSize(string $name, int $width = 0, int $height = 0, $crop = false)
	{
		return add_image_size($name, $width, $height, $crop);
	}
}
