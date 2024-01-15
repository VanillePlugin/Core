<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.0.0
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\lib;

use VanillePlugin\exc\CacheException;
use VanillePlugin\inc\Cache as ObjectCache;
use VanillePlugin\third\Cache as ThirdCache;
use VanilleCache\Cache as FileCache;

/**
 * Plugin cache manager.
 */
final class Cache
{
	use \VanillePlugin\VanillePluginConfig;

	/**
	 * @access private
	 * @var string FILECACHE, File cache
	 */
	private const FILECACHE = 'VanilleCache\Cache';

	/**
	 * @access private
	 * @var string $key, Cache key
	 * @var string $tag, Cache tag
	 * @var array $path, Cache path
	 */
	private $key;
	private $tag;
	private $path;

	/**
	 * Init cache path.
     */
	public function __construct()
	{
		// Init plugin config
		$this->initConfig();

		// Set cache path
		$this->path[] = $this->getTempPath();
		$this->path[] = $this->getCachePath();

		// Reset config
		$this->resetConfig();
	}

	/**
	 * Set cache key.
	 * 
	 * @access public
	 * @param string $key
	 * @return object
	 */
	public function setKey(string $key) : self
	{
		$this->key = $key;
		$this->tag = $this->getTag($key);

		if ( ThirdCache::isActive() ) {
			$this->key = $this->applyNamespace($this->key);
			$this->tag = $this->applyNamespace($this->tag);
		}

		return $this;
	}

	/**
	 * Get cache value.
	 * 
	 * @access public
	 * @return mixed
	 * @throws CacheException
	 */
	public function get()
	{
		if ( !$this->key ) {
	        throw new CacheException(
	            CacheException::undefinedCacheKey()
	        );
		}

		if ( ThirdCache::isActive() ) {
			return ObjectCache::get($this->key);
		}

		if ( $this->isType('class', self::FILECACHE) ) {
			return (new FileCache())->get($this->key);
		}

		return null;
	}

	/**
	 * Check cache.
	 *
	 * @access public
	 * @return bool
	 * @throws CacheException
	 */
	public function isCached() : bool
	{
		if ( !$this->key ) {
	        throw new CacheException(
	            CacheException::undefinedCacheKey()
	        );
		}

		if ( ThirdCache::isActive() ) {
			return (ObjectCache::get($this->key) !== false);
		}

		if ( $this->isType('class', self::FILECACHE) ) {
			return (new FileCache())->setKey($this->key)->isCached();
		}

		return false;
	}

	/**
	 * Set cache value.
	 *
	 * @access public
	 * @param mixed $value
	 * @param int $ttl
	 * @return bool
	 * @throws CacheException
	 */
	public function set($value, ?int $ttl = null) : bool
	{
		if ( !$this->key ) {
	        throw new CacheException(
	            CacheException::undefinedCacheKey()
	        );
		}

		if ( ThirdCache::isActive() ) {
			return ObjectCache::set($this->key, $value, $this->tag, (int)$ttl);
		}

		if ( $this->isType('class', self::FILECACHE) ) {
			return (new FileCache())->setKey($this->key)->set($value, $this->tag, $ttl);
		}

		return false;
	}

	/**
	 * Delete cache.
	 * 
	 * @access public
	 * @return bool
	 * @throws CacheException
	 */
	public function delete() : bool
	{
		if ( !$this->key ) {
	        throw new CacheException(
	            CacheException::undefinedCacheKey()
	        );
		}

		if ( ThirdCache::isActive() ) {
			return ObjectCache::delete($this->key);
		}

		if ( $this->isType('class', self::FILECACHE) ) {
			return (new FileCache())->delete($this->key);
		}

		return false;
	}
	
	/**
	 * Delete cache by tag(s).
	 * 
	 * @access public
	 * @param mixed $tag
	 * @return bool
	 */
	public function deleteByTag($tag) : bool
	{
		if ( $this->isType('class', self::FILECACHE) ) {
			return (new FileCache())->deleteByTag($tag);
		}
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
		$stauts = 0;
		
		if ( ThirdCache::isActive() ) {
			$stauts += (int)ThirdCache::purge();
			$stauts += (int)ObjectCache::purge();
		}

		if ( $this->isType('class', self::FILECACHE) ) {
			$stauts += (int)(new FileCache())->purge();
		}

		if ( $force ) {
			foreach ($this->path as $path) {
				$stauts += (int)$this->clearDir($path, [
					$this->getNameSpace()
				]);
			}
		}

		return (bool)$stauts;
	}

	/**
	 * Generate cache key.
	 * 
	 * @access public
	 * @param string $item
	 * @param array $args
	 * @return string
	 */
	public function generateKey(string $item, array $args = []) : string
	{
		$key = $item;
		
		foreach ($args as $name => $value) {

			if ( $this->isType('array', $value) 
			  || $this->isType('null', $value) 
			  || $this->isType('empty', $value) ) {
				continue;
			}

			if ( $value === 0 ) {
				$value = '0';

			} elseif ( $this->isType('false', $value) ) {
				$value = 'false';

			} elseif ( $this->isType('true', $value) ) {
				$value = 'true';
			}

			$key .= "-{$name}-{$value}";
		}

		return $key;
	}

	/**
	 * Get cache tag.
	 * 
	 * @access private
	 * @param string $key
	 * @return string
	 */
	private function getTag(string $key) : string
	{
		$tag = explode('-', $key);
		return $tag[0] ?? $this->getNameSpace();
	}
}
