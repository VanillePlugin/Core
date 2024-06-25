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

namespace VanillePlugin\lib;

/**
 * Plugin cache manager.
 */
final class Cache
{
	use \VanillePlugin\VanillePluginOption;

	/**
	 * @access public
	 * @var string INTERNAL, Internal cache
	 * @var string THIRD, Third-Party cache
	 */
	public const INTERNAL = '\VanilleCache\Cache';
	public const THIRD    = '\VanilleThird\Cache';

	/**
	 * Get cache value.
	 *
	 * @access public
	 * @param string $key
	 * @param bool $status
	 * @param bool $group
	 * @return mixed
	 */
	public function get(string $key, ?bool &$status = null, ?string $group = null)
	{
		if ( $this->hasInternalCache() ) {
			if ( !$this->hasPluginFilter('get-cache') ) {
				$instance = self::INTERNAL;
				$cache = new $instance();
				return $cache->get($key, $status);
			}
		}
		return $this->getPluginCache($key, $status, $group);
	}

	/**
	 * Set cache value.
	 *
	 * @access public
	 * @param string $key
	 * @param mixed $value
	 * @param int $ttl
	 * @return bool
	 */
	public function set(string $key, $value, ?int $ttl = null, ?string $group = null) : bool
	{
		if ( $this->hasInternalCache() ) {
			if ( !$this->hasPluginFilter('set-cache') ) {
				$instance = self::INTERNAL;
				$cache = new $instance;
				return $cache->set($key, $value, $ttl, $group);
			}
		}

		return $this->setPluginCache($key, $value, $ttl, $group);
	}

	/**
	 * Delete cache.
	 *
	 * @access public
	 * @param string $key
	 * @return bool
	 */
	public function delete(string $key, ?string $group = null) : bool
	{
		if ( $this->hasInternalCache() ) {
			if ( !$this->hasPluginFilter('delete-cache') ) {
				$instance = self::INTERNAL;
				$cache = new $instance;
				return $cache->delete($key);
			}
		}

		return $this->deletePluginCache($key, $group);
	}

	/**
	 * Purge any cache.
	 *
	 * @access public
	 * @param bool $force
	 * @return bool
	 */
	public function purge(bool $force = false) : bool
	{
		$count = 0;
		$count += (int)$this->purgePluginCache();

		if ( $this->hasInternalCache() ) {
			if ( !$this->hasPluginFilter('purge-cache') ) {
				$instance = self::INTERNAL;
				$cache  = new $instance;
				$count += (int)$cache::purge();
			}
		}

		if ( $this->hasThirdCache() ) {
			$cache  = self::THIRD;
			$count += (int)$cache::purge();
		}

		if ( $force ) {
			$path[] = $this->getTempPath();
			$path[] = $this->getCachePath();
			foreach ($path as $path) {
				$count += (int)$this->clearDir($path, [
					$this->getNameSpace()
				]);
			}
		}

		return (bool)$count;
	}

	/**
	 * Check third-party cache.
	 *
	 * @access public
	 * @return bool
	 */
	public function hasThirdCache() : bool
	{
		if ( $this->isType('class', self::THIRD) ) {
			$cache = self::THIRD;
			if ( $this->hasObject('method', $cache, 'isActive') ) {
				return $cache::isActive();
			}
		}
		return false;
	}

	/**
	 * Check internal cache.
	 *
	 * @access public
	 * @return bool
	 */
	public function hasInternalCache() : bool
	{
		return $this->isType('class', self::INTERNAL);
	}
}
