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
 */

namespace VanillePlugin\lib;

use VanillePlugin\int\NameSpaceInterface;
use VanillePlugin\int\PluginNameSpaceInterface;

class Session extends PluginOptions implements NameSpaceInterface
{
	/**
	 * @param PluginNameSpaceInterface $plugin
	 * @return void
	 */
	public function __construct(PluginNameSpaceInterface $plugin)
	{
		// Init plugin config
		$this->initConfig($plugin);
		wp_session_start();
	}

	/**
	 * @param void
	 * @return void
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
	public function set($name, $value = '')
	{
		$_SESSION[$this->getNameSpace()][$name] = $value;
	}

	/**
	 * Wrapp Wordpress session
	 *
	 * @access public
	 * @param void
	 * @return void
	 */
	public function get($name)
	{
		if ( isset($_SESSION[$this->getNameSpace()][$name]) ) {
			return $_SESSION[$this->getNameSpace()][$name];
		} else return false;
	}

	/**
	 * Wrapp Wordpress session
	 *
	 * @access public
	 * @param void
	 * @return void
	 */
	public function isSetted()
	{
		if (session_id()) return true;
	}
}
