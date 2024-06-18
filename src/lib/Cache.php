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

use VanillePlugin\exc\CacheException;
use VanillePlugin\inc\Cache as ObjectCache;
use VanilleThird\Cache as ThirdCache;
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
	 * @var string THIRDCACHE, Third cache
	 */
	private const FILECACHE  = 'VanilleCache\Cache';
	private const THIRDCACHE = 'VanilleThird\Cache';

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
	 *
	 * @param string $key
	 * @param string $tag
     */
	public function __construct(?string $key = null, ?string $tag = null)
	{
		// Set cache key
		$this->setKey($key);

		// Set cache tag
		$this->setTag($tag);

		// Set cache path
		$this->path[] = $this->getTempPath();
		$this->path[] = $this->getCachePath();

		// Reset config
		$this->resetConfig();
	}

	/**
	 * Get cache value.
	 *
	 * @access public
	 * @param mixed $default
	 * @return mixed
	 */
	public function get($default = null)
	{
		if ( !$this->key ) {
	        throw new CacheException(
	            CacheException::undefinedCacheKey()
	        );
		}

		$data = null;

		if ( $this->hasThirdCache() ) {
			$data = ObjectCache::get($this->key);

		} elseif ( $this->hasFileCache() ) {
			$data = (new FileCache())->get($this->key);
		}

		if ( $this->isType('null', $data)) {
			$data = $default;
		}

		return $data;
	}

	/**
	 * Check cache.
	 *
	 * @access public
	 * @return bool
	 */
	public function isCached() : bool
	{
		if ( !$this->key ) {
	        throw new CacheException(
	            CacheException::undefinedCacheKey()
	        );
		}

		if ( $this->hasThirdCache() ) {
			return (ObjectCache::get($this->key) !== false);
		}

		if ( $this->hasFileCache() ) {
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
	 */
	public function set($value, ?int $ttl = null) : bool
	{
		if ( !$this->key ) {
	        throw new CacheException(
	            CacheException::undefinedCacheKey()
	        );
		}

		if ( $this->hasThirdCache() ) {
			return ObjectCache::set($this->key, $value, (string)$this->tag, (int)$ttl);
		}

		if ( $this->hasFileCache() ) {
			return (new FileCache())->setKey($this->key)->set($value, $this->tag, $ttl);
		}

		return false;
	}

	/**
	 * Delete cache.
	 *
	 * @access public
	 * @return bool
	 */
	public function delete() : bool
	{
		if ( !$this->key ) {
	        throw new CacheException(
	            CacheException::undefinedCacheKey()
	        );
		}

		if ( $this->hasThirdCache() ) {
			return ObjectCache::delete($this->key);
		}

		if ( $this->hasFileCache() ) {
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
		if ( $this->hasFileCache() ) {
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
		
		if ( $this->hasThirdCache() ) {
			$stauts += (int)ThirdCache::purge();
			$stauts += (int)ObjectCache::purge();
		}

		if ( $this->hasFileCache() ) {
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
	 * Set cache key.
	 *
	 * @access public
	 * @param string $key
	 * @return object
	 */
	public function setKey(?string $key = null) : self
	{
		if ( $key ) {
			$this->key = $this->applyNamespace($key);
		}
		return $this;
	}

	/**
	 * Set cache tag.
	 *
	 * @access public
	 * @param string $tag
	 * @return object
	 */
	public function setTag(?string $tag = null) : self
	{
		if ( $tag ) {
			$this->tag = $tag;

		} else {
			if ( $this->key ) {
				$tag = explode('-', $this->key);
				$this->tag = $tag[1] ?? $this->getNameSpace();
			}
		}
		return $this;
	}

	/**
	 * Check third cache.
	 *
	 * @access public
	 * @return bool
	 */
	public function hasThirdCache() : bool
	{
		if ( $this->isType('class', self::THIRDCACHE) ) {
			return ThirdCache::isActive();
		}
		return false;
	}

	/**
	 * Check file cache.
	 *
	 * @access public
	 * @return bool
	 */
	public function hasFileCache() : bool
	{
		return $this->isType('class', self::FILECACHE);
	}
}
