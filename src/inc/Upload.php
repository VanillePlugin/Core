<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.0.2
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
	 * Move uploaded file.
	 * 
	 * @access public
	 * @param string $temp
	 * @param string $path
	 * @return bool
	 */
	public static function moveUploadedFile(string $temp, string $path) : bool
	{
		return move_uploaded_file($temp, $path);
	}

	/**
	 * Handle uploaded file.
	 * 
	 * @access public
	 * @param array $file, $_FILES
	 * @param array $args, Override default args
	 * @param string $time
	 * @return mixed
	 */
	public static function handle($file, $args = [], $time = null)
	{
		// Validate global file
		if ( !TypeCheck::isArray($file) ) {
			return false;
		}

		// Include upload handler
		if ( !TypeCheck::isFunction('wp_handle_upload') ) {
		    require_once GlobalConst::rootDir('wp-admin/includes/file.php');
		}

		// Set defaut handler args
		if ( !$args ) {
			$args = [
				'test_form' => false
			];
		}

		return wp_handle_upload($file, $args, $time);
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
		return wp_upload_dir($time, $create, $refresh);
	}
}
