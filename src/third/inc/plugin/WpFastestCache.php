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

namespace VanillePlugin\third\inc\plugin;

/**
 * WP Fastest Cache plugin helper class.
 * 
 * @see https://github.com/emrevona/wp-fastest-cache
 */
final class WpFastestCache
{
	/**
	 * Check whether plugin is enabled.
	 * 
	 * @access public
	 * @return bool
	 */
	public static function isEnabled() : bool
	{
		return defined('WPFC_MAIN_PATH');
	}
	
	/**
	 * Purge cache.
	 * 
	 * @access public
	 * @return bool
	 * @internal
	 */
	public static function purge() : bool
	{
		if ( method_exists('\WpFastestCache','deleteCache') ) {
			$cache = new \WpFastestCache();
			$cache->deleteCache();
			return true;
		}
		return false;
	}
}
