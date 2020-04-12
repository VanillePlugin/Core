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

use VanilleNameSpace\core\system\libraries\interfaces\ConfigInterface;

class Option extends WordPress
{
	/**
	 * @access private
	 * @var string $prefix
	 */
	private $prefix;

	/**
	 * Register a settings and its data
	 *
	 * @since 4.0.0
	 * @version 5.4
	 * @access protected
	 * @param inherit
	 * @return inherit
	 */
	protected static function addPluginOption($group, $name, $args = null)
	{
		$prefix = Config::get('prefix');
		return parent::addOption("{$prefix}{$group}", "{$prefix}{$name}", $args);
	}

	/**
	 * Retrieves an option value based on an option name
	 *
	 * @see /reference/functions/get_option/
	 * @since 4.0.0
	 * @version 5.4
	 * @access protected
	 * @param inherit
	 * @return inherit
	 */
	protected static function getPluginOption($name, $default = null)
	{
		$prefix = Config::get('prefix');
		return parent::getOption("{$prefix}{$name}",$default);
	}

	/**
	 * Retrieves an option value based on an option name as object
	 *
	 * @see /reference/functions/get_option/
	 * @since 4.0.0
	 * @access protected
	 * @param string $name
	 * @return object
	 */
	protected function getPluginObject($name)
	{
		$prefix = Config::get('prefix');
		return Data::toObject( parent::getOption("{$prefix}{$name}") );
	}

	/**
	 * Update the value of an option that was already added
	 *
	 * @see /reference/functions/update_option/
	 * @since 4.0.0
	 * @access protected
	 * @param string $name, mixed $value
	 * @return boolean
	 */
	protected static function updatePluginOption($name, $value)
	{
		$prefix = Config::get('prefix');
		return parent::updateOption("{$prefix}{$name}",$value);
	}

	/**
	 * Update the value of an option that was already added
	 *
	 * @see /reference/functions/delete_option/
	 * @since 4.0.0
	 * @access protected
	 * @param string $name
	 * @return {inherit}
	 */
	protected static function removePluginOption($name)
	{
		$prefix = Config::get('prefix');
		return parent::removeOption("{$prefix}{$name}");
	}
}
