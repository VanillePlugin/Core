<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.7.7
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\lib;

use VanillePlugin\int\PluginNameSpaceInterface;
use VanillePlugin\inc\Stringify;
use VanillePlugin\inc\TypeCheck;
use VanillePlugin\thirdparty\Cache as ThirdPartyCache;

/**
 * Wrapper Class for Cache.
 */
final class Cache extends PluginOptions
{
	/**
	 * @access private
	 * @var int $ttl, cache TTL
	 */
	private static $ttl = false;

	/**
	 * @param PluginNameSpaceInterface $plugin
	 */
	public function __construct(PluginNameSpaceInterface $plugin)
	{
		// Init plugin config
		$this->initConfig($plugin);

		// Set default ttl
		if ( !self::$ttl ) {
			self::expireIn($this->getExpireIn());
		}
	}

	/**
	 * Retrieves the cache contents from the cache by key and group.
	 * 
	 * @access public
	 * @param int|string $key
	 * @param string $group
	 * @param bool $force
	 * @param bool $found
	 * @return mixed|false
	 */
	public function get($key, $group = '', $force = false, $found = null)
	{
		return wp_cache_get($this->formatKey($key),$group,$force,$found);
	}

	/**
	 * Saves the data to the cache.
	 * 
	 * @access public
	 * @param int|string $key
	 * @param mixed $data
	 * @param string $group
	 * @param int $expire
	 * @return bool
	 */
	public function set($key, $data, $group = '', $expire = null)
	{
		$expire = TypeCheck::isInt($expire) ? $expire : self::$ttl;
		return wp_cache_set($this->formatKey($key),$data,$group,$expire);
	}

	/**
	 * Adds data to the cache, if the cache key doesn’t already exist.
	 * 
	 * @access public
	 * @param int|string $key
	 * @param mixed $data
	 * @param string $group
	 * @param int $expire
	 * @return bool
	 */
	public function add($key, $data, $group = '', $expire = null)
	{
		$expire = TypeCheck::isInt($expire) ? $expire : self::$ttl;
		return wp_cache_add($this->formatKey($key),$data,$group,$expire);
	}

	/**
	 * Replaces the contents of the cache with new data.
	 * 
	 * @access public
	 * @param int|string $key
	 * @param mixed $data
	 * @param string $group
	 * @param int $expire
	 * @return bool
	 */
	public function update($key, $data, $group = '', $expire = null)
	{
		$expire = TypeCheck::isInt($expire) ? $expire : self::$ttl;
		return wp_cache_replace($this->formatKey($key),$data,$group,$expire);
	}

	/**
	 * Removes the cache contents matching key and group.
	 * 
	 * @access public
	 * @param int|string $key
	 * @param string $group
	 * @return bool
	 */
	public function delete($key, $group = '')
	{
		return wp_cache_delete($this->formatKey($key),$group);
	}

	/**
	 * @access public
	 * @param void
	 * @return void
	 */
	public function purge()
	{
		wp_cache_flush();
	}

	/**
	 * @access public
	 * @param int $ttl 30
	 * @return void
	 */
	public static function expireIn($ttl = 30)
	{
		self::$ttl = intval($ttl);
	}

	/**
	 * @access public
	 * @param void
	 * @return void
	 */
	public static function removeThirdParty()
	{
		// Clear WordPress 3rd-party cache
		ThirdPartyCache::purge();
	}

	/**
	 * @access public
	 * @param int|string $key
	 * @return string
	 */
	private function formatKey($key)
	{
		$key = Stringify::formatKey($key);
		return "{$this->getNameSpace()}-{$key}";
	}
}
