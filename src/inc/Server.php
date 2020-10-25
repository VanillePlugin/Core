<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.2.8
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
	 * @param string $item null
	 * @return mixed
	 */
	public static function get($item = null)
	{
		if ($item) {
			return self::isSetted($item) ? $_SERVER[$item] : false;
		} else {
			return $_SERVER;
		}
	}

	/**
	 * @access public
	 * @param string $item
	 * @param mixed $value
	 * @return void
	 */
	public static function set($item, $value)
	{
		$_SERVER[$item] = $value;
	}

	/**
	 * @access public
	 * @param string $item null
	 * @return boolean
	 */
	public static function isSetted($item = null)
	{
		if ($item) {
			return isset($_SERVER[$item]);
		} else {
			return isset($_SERVER);
		}
	}

	/**
	 * @access public
	 * @param void
	 * @return boolean
	 */
	public static function isHttps()
	{
		if ( self::isSetted('HTTPS') 
			&& !empty(self::get('HTTPS')) 
			&& self::get('HTTPS') !== 'off' ) {
		    return true;
		}
		return false;
	}
	
	/**
	 * @access public
	 * @param void
	 * @return mixed
	 */
	public static function getRemote()
	{
		$remote = self::isSetted('HTTP_X_FORWARDED_FOR') 
		? self::get('HTTP_X_FORWARDED_FOR') : false;
		if ( !$remote ) {
			$remote = self::isSetted('REMOTE_ADDR') 
			? self::get('REMOTE_ADDR') : false;
		}
		return $remote;
	}

	/**
	 * @access public
	 * @param void
	 * @return string
	 */
	public static function getProtocol()
	{
		return Server::isHttps() ? 'https://' : 'http://';
	}
}
