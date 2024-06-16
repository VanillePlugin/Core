<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.0.x
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\inc;

/**
 * Wrapper class for Transients API.
 */
final class Transient
{
	/**
	 * Get transient.
	 *
	 * @access public
	 * @param string $key
	 * @return mixed
	 */
	public static function get(string $key)
	{
		$key = self::formatKey($key);
		return get_transient($key);
	}

	/**
	 * Get site transient.
	 *
	 * @access public
	 * @param string $key
	 * @return mixed
	 */
	public static function getSite(string $key)
	{
		$key = self::formatKey($key);
		return get_site_transient($key);
	}

	/**
	 * Set transient.
	 * 
	 * @access public
	 * @param string $key
	 * @param mixed $value
	 * @param int $ttl
	 * @return bool
	 */
	public static function set(string $key, $value, int $ttl = 0) : bool
	{
		$key = self::formatKey($key);
		return set_transient($key, $value, $ttl);
	}

	/**
	 * Set site transient.
	 * 
	 * @access public
	 * @param string $key
	 * @param mixed $value
	 * @param int $ttl
	 * @return bool
	 */
	public static function setSite(string $key, $value, int $ttl = 0) : bool
	{
		$key = self::formatKey($key);
		return set_site_transient($key, $value, $ttl);
	}

	/**
	 * Delete transient.
	 * 
	 * @access public
	 * @param string $key
	 * @return bool
	 */
	public static function delete(string $key) : bool
	{
		$key = self::formatKey($key);
		return delete_transient($key);
	}

	/**
	 * Delete site transient.
	 * 
	 * @access public
	 * @param string $key
	 * @return bool
	 */
	public static function deleteSite(string $key) : bool
	{
		$key = self::formatKey($key);
		return delete_site_transient($key);
	}

	/**
	 * Format transient key.
	 *
	 * @access private
	 * @param string $key
	 * @return string
	 */
	private static function formatKey(string $key) : string
	{
		$key = Stringify::slugify($key);
		$formatted = substr($key, 0, 172);
		if ( $formatted ) {
			return $formatted;
		}
		return $key;
	}
}
