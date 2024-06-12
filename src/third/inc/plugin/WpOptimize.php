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
 * WP-Optimize plugin helper class.
 * 
 * @see https://github.com/DavidAnderson684/WP-Optimize
 */
final class WpOptimize
{
	/**
	 * Check whether plugin is enabled.
	 * 
	 * @access public
	 * @return bool
	 */
	public static function isEnabled() : bool
	{
		return defined('WPO_VERSION');
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
		if ( class_exists('\WP_Optimize_Cache_Commands') ) {
            $wpoptimize = new \WP_Optimize_Cache_Commands();
            $wpoptimize->purge_page_cache();
            return true;
		}
		return false;
	}
}
