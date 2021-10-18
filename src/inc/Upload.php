<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.7.2
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\inc;

final class Upload
{
	/**
	 * @access public
	 * @param string $item
	 * @return mixed
	 */
	public static function get($item = null)
	{
		if ( $item ) {
			return self::isSetted($item) ? $_FILES[$item] : false;
		} else {
			return $_FILES;
		}
	}

	/**
	 * @access public
	 * @param string $item
	 * @param mixed $value
	 * @return void
	 */
	public static function set($item, $value = null)
	{
		$_FILES[$item] = $value;
	}
	
	/**
	 * @access public
	 * @param string $item
	 * @return bool
	 */
	public static function isSetted($item = null)
	{
		if ( $item ) {
			return isset($_FILES[$item]);
		} else {
			return isset($_FILES) && !empty($_FILES);
		}
	}

	/**
	 * @access public
	 * @param string $upload
	 * @param string $file
	 * @return mixed
	 */
	public static function doUpload($upload, $file = null)
	{
		if ( self::isSetted() ) {
			if ( !$_FILES['file']['error'] ) {
				$tmp = ($file) ? $file : $_FILES['file']['tmp_name'];
				$name = ($file) ? basename($file) : $_FILES['file']['name'];
				self::moveUploadedFile($tmp,"{$upload}/{$name}");
				return "{$upload}/{$name}";
			}
		}
		return false;
	}

	/**
	 * @access public
	 * @param string $tmp
	 * @param string $file
	 * @return bool
	 */
	public static function moveUploadedFile($tmp, $file)
	{
		return move_uploaded_file($tmp,$file);
	}

	/**
	 * @access public
	 * @param string $file
	 * @param array $args
	 * @param string $time
	 * @return array
	 */
	public static function handle($file, $args = [], $time = null)
	{
		if ( !TypeCheck::isFunction('wp_handle_upload') ) {
		    require_once(GlobalConst::rootDir('wp-admin/includes/file.php'));
		}
		if ( empty($args) ) {
			$args = ['test_form' => false];
		}
		return wp_handle_upload($file,$args,$time);
	}

	/**
	 * @access public
	 * @param int $time
	 * @param bool $create
	 * @param bool $refresh
	 * @return array
	 */
	public static function dir($time = null, $create = true, $refresh = false)
	{
		return wp_upload_dir($time,$create,$refresh);
	}
}
