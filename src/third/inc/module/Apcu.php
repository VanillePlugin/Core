<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.0.1
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\third\inc\module;

use VanillePlugin\inc\TypeCheck;

/**
 * APCu module helper class.
 * 
 * @see https://www.php.net/manual/en/function.apcu-clear-cache.php
 */
final class Apcu
{
	/**
	 * Check module plugin is enabled.
	 * 
	 * @access public
	 * @return bool
	 */
	public static function isEnabled() : bool
	{
		return TypeCheck::isFunction('apcu_clear_cache');
	}

	/**
	 * Purge cache.
	 * 
	 * @access public
	 * @return bool
	 */
	public static function purge() : bool
	{
		return apcu_clear_cache();
	}
}
