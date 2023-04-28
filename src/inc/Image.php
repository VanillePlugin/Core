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

declare(strict_types=1);

namespace VanillePlugin\inc;

final class Image extends File
{
	/**
	 * Import image from URL.
	 *
	 * @access public
	 * @param array $url
	 * @param bool $override
	 * @return mixed
	 */
	public static function import($url, $override = true)
	{
		// Check valid image
		if ( !($name = self::validate($url)) ) {
			return false;
		}

		// Set image upload data
		$data = self::getMime($name);
		$mime = $data['type'];
		$dir  = Upload::dir();
		$path = "{$dir['path']}/{$name}"; // Keep non formated path

		// Get existing image from gallery by filename (Title)
		if ( ($id = Attachment::getIdByTitle(self::getName($path))) ) {

		    return [
		    	'id'  => $id,
		    	'url' => Attachment::getUrlById($id)
		    ];

		} else {

			// Duplicate image
			if ( !$override ) {
				if ( self::exists($path) ) {
					$ext  = $data['ext'];
					$tmp  = self::getName($name);
					$id   = Tokenizer::getUniqueId();
					$name = "{$tmp}-{$id}.{$ext}";
					$path = "{$dir['path']}/{$name}";
				}
			}

			// Import image
			if ( !parent::import($url,$path) ) {
				return false;
			}

			// Insert image attachment
			return Attachment::insert($path,[
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
	public static function upload($args = [])
	{
		// Get file from global
		$file = Upload::isSetted('file') 
		? (array)Upload::get('file') : [];

		// Check valid image mime
		if ( !self::validate($file['name']) ) {
			return false;
		}

		// Set image upload data
		$data = Upload::handle($file,$args);

		// Insert image attachment
		return Attachment::insert($data['file'],$data);
	}

	/**
	 * Validate image file mime.
	 *
	 * @access public
	 * @param string $file
	 * @param array $allowed
	 * @return mixed
	 */
	public static function validate($file, $allowed = [])
	{
		$allowed = self::getAllowedMimes($allowed);
		return parent::validate($file,$allowed);
	}

	/**
	 * Get allowed images mimes.
	 *
	 * @access public
	 * @param array $allowed
	 * @return array
	 */
	public static function getAllowedMimes($allowed = [])
	{
		return Arrayify::merge([
			'jpg'  => 'image/jpeg',
			'jpeg' => 'image/jpeg',
			'bmp'  => 'image/bmp',
			'png'  => 'image/png',
			'gif'  => 'image/gif'
		], $allowed);
	}
}
