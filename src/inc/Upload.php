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

final class Upload
{
	/**
	 * Get _FILES value.
	 *
	 * @access public
	 * @param string $key
	 * @return mixed
	 */
	public static function get(?string $key = null)
	{
		if ( $key ) {
			return self::isSetted($key) ? $_FILES[$key] : null;
		}
		return self::isSetted() ? $_FILES : null;
	}

	/**
	 * Set _FILES value.
	 *
	 * @access public
	 * @param string $key
	 * @param mixed $value
	 * @return void
	 */
	public static function set(string $key, $value = null)
	{
		$_FILES[$key] = $value;
	}

	/**
	 * Check _FILES value.
	 *
	 * @access public
	 * @param string $key
	 * @return bool
	 */
	public static function isSetted(?string $key = null) : bool
	{
		if ( $key ) {
			return isset($_FILES[$key]);
		}
		return isset($_FILES) && !empty($_FILES);
	}

    /**
     * Unset _FILES value.
     *
     * @access public
     * @param string $key
     * @return void
     */
    public static function unset(?string $key = null)
    {
        if ( $key ) {
            unset($_FILES[$key]);

        } else {
            $_FILES = [];
        }
    }

	/**
	 * Move uploaded files.
	 *
	 * @access public
	 * @param string $from
	 * @param string $to
	 * @return bool
	 */
	public static function move(string $from, string $to) : bool
	{
		$to = Stringify::formatPath($to);
		return move_uploaded_file($from, $to);
	}

	/**
	 * Sanitize uploaded files.
	 *
	 * @access public
	 * @param array $files, $_FILES
	 * @param array $types, Mime types
	 * @return array
	 */
	public static function sanitize(array $files, ?array $types = []) : array
	{
		$data  = [];
		$types = self::getMimes($types);

		foreach ($files as $file) {

			if ( $file['error'] ) {
				continue;
			}

			$name = $file['name'];
			if ( !($ext = File::getExtension($name)) ) {
				continue;
			}

			$temp = $file['tmp_name'];
			$type = File::getMimeType($temp, $ext, $types);
			if ( $type == 'undefined' ) {
				continue;
			}

			if ( !Validator::isValidMime($name, $types) ) {
				continue;
			}

			$rand = Tokenizer::getUniqueId(false);
			$name = Stringify::remove(".{$ext}", $name);
			$name = Stringify::slugify("{$name}");
			$path = "{$name}.{$ext}";
			$path = (substr($path, 0, 1) !== '.') ? "{$rand}-{$path}" : "{$rand}{$path}";

			$key = (!empty($name)) ? $name : $ext;
			$data[$key] = [
				'path' => $path,
				'temp' => $temp
			];

		}

		return $data;
	}

	/**
	 * Handle uploaded file.
	 *
	 * @access public
	 * @param array $file, single $_FILES
	 * @param array $args, Override
	 * @param string $time
	 * @return array
	 */
	public static function handle(array $file, array $args = [], ?string $time = null) : array
	{
		$args = (!$args) ? ['test-form' => false] : $args;

		if ( !TypeCheck::isFunction('wp-handle-upload') ) {
		    require_once Globals::rootDir('wp-admin/includes/file.php');
		}

		return wp_handle_upload($file, Format::undash($args), $time);
	}

	/**
	 * Get upload directory.
	 *
	 * @access public
	 * @param string $sub
	 * @return string
	 */
	public static function getDir(?string $sub = null) : string
	{
		$dir  = Globals::upload();
		$path = $dir['basedir'] ?? '';
		if ( $sub ) {
			$path .= "/{$sub}";
		}
		return Stringify::formatPath($path);
	}

	/**
	 * Get upload URL.
	 *
	 * @access public
	 * @param string $sub
	 * @return string
	 */
	public static function getUrl(?string $sub = null) : string
	{
		$dir = Globals::upload();
		$url = $dir['baseurl'] ?? '';
		if ( $sub ) {
			$url .= "/{$sub}";
		}
		return Stringify::formatPath($url);
	}

	/**
	 * Get upload allowed mime types.
	 *
	 * @access public
	 * @param array $types
	 * @return array
	 */
	public static function getMimes(?array $types = []) : array
	{
		$mimes = [
			'txt'  => 'text/plain',
			'csv'  => 'text/csv',
			'tsv'  => 'text/tab-separated-values',
			'ics'  => 'text/calendar',
			'rtx'  => 'text/richtext',
			'jpg'  => 'image/jpeg',
			'jpeg' => 'image/jpeg',
			'gif'  => 'image/gif',
			'png'  => 'image/png',
			'bmp'  => 'image/bmp',
			'mp3'  => 'audio/mpeg',
			'ogg'  => 'audio/ogg',
			'wav'  => 'audio/wav',
			'mp4'  => 'video/mp4',
			'mpeg' => 'video/mpeg',
			'ogv'  => 'video/ogg',
			'zip'  => 'application/zip',
			'rar'  => 'application/rar',
			'7z'   => 'application/x-7z-compressed',
			'pdf'  => 'application/pdf',
			'doc'  => 'application/msword',
			'xls'  => 'application/vnd.ms-excel',
			'xla'  => 'application/vnd.ms-excel',
			'ppt'  => 'application/vnd.ms-powerpoint',
			'mdb'  => 'application/vnd.ms-access',
			'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
			'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
		];
		return Arrayify::merge($types, $mimes);
	}
}
