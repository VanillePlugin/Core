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

namespace VanillePlugin\third\inc\plugin;

/**
 * Kinsta (MU) plugin helper class.
 * 
 * @see https://github.com/retlehs/kinsta-mu-plugins
 */
final class Kinsta
{
	/**
	 * Check whether plugin is enabled.
	 * 
	 * @access public
	 * @return bool
	 */
	public static function isEnabled() : bool
	{
		return defined('KINSTAMU_VERSION');
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
		if ( class_exists('\Kinsta\Cache') ) {
			global $kinsta_cache;
            if ( is_object($kinsta_cache) ) {
            	$kinsta_cache->kinsta_cache_purge->purge_complete_caches();
            }
            return true;
		}
		return false;
	}
}
