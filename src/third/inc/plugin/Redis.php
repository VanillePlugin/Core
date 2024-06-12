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
 * Redis Object Cache plugin helper class.
 * 
 * @see https://github.com/rhubarbgroup/redis-cache
 */
final class Redis
{
	/**
	 * Check whether plugin is enabled.
	 * 
	 * @access public
	 * @return bool
	 */
	public static function isEnabled() : bool
	{
		return class_exists('\Rhubarb\RedisCache\Plugin');
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
		global $wp_object_cache;
		if ( method_exists($wp_object_cache, 'redis_instance') ) {
			return $wp_object_cache->redis_instance()->flushall();
		}
		return false;
	}
}
