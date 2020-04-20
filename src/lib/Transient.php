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

class Transient extends PluginOptions
{
	/**
	 * Retrieves an option value based on an option name
	 *
	 * @see /reference/functions/get_transient/
	 * @since 4.0.0
	 * @access protected
	 * @param string $name, null string $default
	 * @return mixed
	 */
	protected static function getTransient($name)
	{
		$prefix = Config::get('prefix');
		return get_transient("{$prefix}{$name}");
	}

	/**
	 * Retrieves an option value based on an option name
	 *
	 * @see /reference/functions/set_transient/
	 * @since 4.0.0
	 * @access protected
	 * @param string $name, null string $default
	 * @return mixed
	 */
	protected static function setTransient($name,$value,$expiration = 300 )
	{
		$prefix = Config::get('prefix');
		return set_transient("{$prefix}{$name}",$value,$expiration);
	}

	/**
	 * Retrieves an option value based on an option name
	 *
	 * @see /reference/functions/set_transient/
	 * @since 4.0.0
	 * @access protected
	 * @param string $name, null string $default
	 * @return mixed
	 */
	protected static function deleteTransient($name)
	{
		$prefix = Config::get('prefix');
		delete_transient("{$prefix}{$name}");
	}
}
