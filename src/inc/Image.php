<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.7.6
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\inc;

final class Image
{
	/**
	 * Upload image file.
	 *
	 * @access public
	 * @param array $files
	 * @param array $args
	 * @return mixed
	 */
	public static function upload($file = null, $args = [])
	{
		if ( !$file ) {
			if ( Upload::isSetted('file') ) {
				$file = Upload::get('file');
			}
		}

		// Check valid image
		if ( self::validate($file) ) {
			return false;
		}

		// Set image upload data
		$data = Upload::handle($file,$args);

		// Insert image attachment
		return Attachment::insert($data['path'],$data);
	}

	/**
	 * Import image from url.
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
		$data = File::getMime($name);
		$mime = $data['type'];
		$dir  = Upload::dir();
		$path = "{$dir['path']}/{$name}";

		// Get existing image from gallery by name (Title)
		if ( ($id = Attachment::getIdByTitle(File::getFileName($path))) ) {

		    return [
		    	'id'  => $id,
		    	'url' => Attachment::getUrlById($id)
		    ];

		} else {

			// Duplicate image
			if ( !$override ) {
				if ( File::exists($path) ) {
					$ext  = $data['ext'];
					$tmp  = File::getFileName($name);
					$id   = Tokenizer::getUniqueId();
					$name = "{$tmp}-{$id}.{$ext}";
					$path = "{$dir['path']}/{$name}";
				}
			}

			// Import image
			if ( !File::import($url,$path) ) {
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
	 * Verify image file.
	 *
	 * @access public
	 * @param string $file
	 * @param array $args
	 * @return mixed
	 */
	public static function validate($file, $args = [])
	{
		// Check filename
		if ( empty($name = basename($file)) ) {
			return false;
		}

		// Basic security check by mime type
		$allowed = [
			'jpg'  => 'image/jpeg',
			'jpeg' => 'image/jpeg',
			'bmp'  => 'image/bmp',
			'png'  => 'image/png',
			'gif'  => 'image/gif'
		];
		$allowed = Arrayify::merge($allowed,$args);

		if ( Validator::isValidMime($name,$allowed) ) {
			return $name;
		}

		return false;
	}
}
