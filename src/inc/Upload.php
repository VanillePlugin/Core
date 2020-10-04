<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.1.4
 * @copyright : (c) 2018 - 2020 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\inc;

class Upload
{
	/**
	 * @access public
	 * @param string $item
	 * @return mixed
	 */
	public static function get($item = null)
	{
		if ( isset($item) ) {
			return $_FILES[$item];
		} else return $_FILES;
	}

	/**
	 * @access public
	 * @param string $item
	 * @param mixed $value
	 * @return void
	 */
	public static function set($item,$value)
	{
		$_FILES[$item] = $value;
	}
	
	/**
	 * @access public
	 * @param string $item
	 * @return boolean
	 */
	public static function isSetted($item = null)
	{
		if ( $item && isset($_FILES[$item]) ) {
			return true;
		} elseif ( !$item && isset($_FILES) ) {
			return true;
		} else return false;
	}
}
