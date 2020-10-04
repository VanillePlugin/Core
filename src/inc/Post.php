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

final class Post
{
	/**
	 * @param string|null $item
	 * @return array|string
	 */
	public static function get($item = null)
	{
		if (isset($item)) return $_POST[$item];
		else return $_POST;
	}	

	/**
	 * @param string|null $item,$value
	 * @return void
	 */
	public static function set($item,$value)
	{
		$_POST[$item] = $value;
	}

	/**
	 * @param string|null $item
	 * @return boolean|null
	 */
	public static function isSetted($item = null)
	{
		if ( $item && isset($_POST[$item]) ) return true;
		elseif ( !$item && isset($_POST) ) return true;
		else return false;
	}
}
