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

namespace VanillePlugin\inc;

/**
 * Wrapper class for object cache.
 */
final class Cache
{
	/**
	 * Get cache value.
	 * 
	 * @access public
	 * @param mixed $key
	 * @param string $group
	 * @param bool $force
	 * @param bool $found
	 * @return mixed
	 */
	public static function get($key, string $group = '', bool $force = false, ?bool &$found = null)
	{
		return wp_cache_get($key, $group, $force, $found);
	}

	/**
	 * Set cache value.
	 * 
	 * @access public
	 * @param mixed $key
	 * @param mixed $value
	 * @param string $group
	 * @param int $ttl
	 * @return bool
	 */
	public static function set($key, $value, string $group = '', int $ttl = 0) : bool
	{
		return wp_cache_set($key, $value, $group, $ttl);
	}

	/**
	 * Add value to cache.
	 * 
	 * @access public
	 * @param mixed $key
	 * @param mixed $value
	 * @param string $group
	 * @param int $ttl
	 * @return bool
	 */
	public static function add($key, $value, string $group = '', int $ttl = 0) : bool
	{
		return wp_cache_add($key, $value, $group, $ttl);
	}

	/**
	 * Update cache value.
	 * 
	 * @access public
	 * @param mixed $key
	 * @param mixed $value
	 * @param string $group
	 * @param int $ttl
	 * @return bool
	 */
	public static function update($key, $value, string $group = '', int $ttl = 0) : bool
	{
		return wp_cache_replace($key, $value, $group, $ttl);
	}

	/**
	 * Delete cache.
	 * 
	 * @access public
	 * @param mixed $key
	 * @param string $group
	 * @return bool
	 */
	public static function delete($key, string $group = '') : bool
	{
		return wp_cache_delete($key, $group);
	}

	/**
	 * Purge cache.
	 * 
	 * @access public
	 * @return bool
	 */
	public static function purge() : bool
	{
		return wp_cache_flush();
	}
}
