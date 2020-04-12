<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.0.1
 * @copyright : (c) 2018 - 2020 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 * Allowed to edit for plugin customization
 */

namespace VanillePlugin\lib;

class Session
{
	/**
	 * @access public
	 * @var array $session
	 * @var string $prefix
	 */
	public static $session;
	public static $prefix = 'VanilleNameSpace';

	/**
	 * Construct front
	 * @param void
	 * @return void
	 *
	 */
	public function __construct()
	{
		wp_session_start();
	}

	/**
	 * Construct front
	 * @param void
	 * @return void
	 *
	 */
	public function __destruct()
	{
		wp_session_unset();
	}


	/**
	 * Wrapp Wordpress session
	 *
	 * @access public
	 * @param void
	 * @return void
	 */
	public static function set($name,$value = '')
	{
		$_SESSION[static::$prefix][$name] = $value;
	}

	/**
	 * Wrapp Wordpress session
	 *
	 * @access public
	 * @param void
	 * @return void
	 */
	public static function get($name)
	{
		if ( isset($_SESSION[static::$prefix][$name]) ) {
			return $_SESSION[static::$prefix][$name];
		} else return false;
	}

	/**
	 * Wrapp Wordpress session
	 *
	 * @access public
	 * @param void
	 * @return void
	 */
	public static function isSetted()
	{
		if ( session_id() ) return true;
	}
}
