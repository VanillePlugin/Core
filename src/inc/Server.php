<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.1.6
 * @copyright : (c) 2018 - 2020 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\inc;

class Server
{
	/**
	 * @access public
	 * @param string $item
	 * @return mixed
	 */
	public static function get($item = null)
	{
		if ( isset($item) ) {
			return $_SERVER[$item];
		} else return $_SERVER;
	}

	/**
	 * @access public
	 * @param string $item
	 * @param mixed $value
	 * @return void
	 */
	public static function set($item,$value)
	{
		$_SERVER[$item] = $value;
	}

	/**
	 * @access public
	 * @param string $item
	 * @return boolean
	 */
	public static function isSetted($item = null)
	{
		if ( $item && isset($_SERVER[$item]) ) {
			return true;
		} elseif ( !$item && isset($_SERVER) ) {
			return true;
		} else return false;
	}
	
	/**
	 * @access public
	 * @param void
	 * @return string
	 */
	public static function remote()
	{
		return self::isSetted('REMOTE_ADDR') 
		? self::get('REMOTE_ADDR') 
		: self::get('HTTP_X_FORWARDED_FOR');
	}
}
