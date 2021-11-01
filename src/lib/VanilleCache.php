<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.7.4
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\lib;

use phpFastCache\CacheManager;
use VanillePlugin\int\VanilleCacheInterface;
use VanillePlugin\int\PluginNameSpaceInterface;
use VanillePlugin\inc\File;
use VanillePlugin\inc\Stringify;
use VanillePlugin\inc\TypeCheck;
use VanillePlugin\thirdparty\Cache as ThirdPartyCache;

/**
 * Wrapper Class for External Filecache & Templates
 * Includes Third-Party Cache Helper
 * Cache WordPress Transient
 */
class VanilleCache extends PluginOptions implements VanilleCacheInterface
{
	/**
	 * @access private
	 * @var object $cache, cache object
	 * @var object $adapter, adapter object
	 * @var int $ttl, cache TTL
	 */
	private $cache = false;
	private $adapter = false;
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
		
		// Set adapter default params
		CacheManager::setDefaultConfig([
		    'path'               => $this->getTempPath(),
		    'default_chmod'      => 0755,
		    'securityKey'        => 'private',
		    'cacheFileExtension' => 'db'
		]);

		// Init adapter
		$this->reset();
		$this->adapter = CacheManager::getInstance('Files');
	}

	/**
	 * Clear adapter instances
	 */
	public function __destruct()
	{
		$this->reset();
	}

	/**
	 * Reset cache instance
	 *
	 * @access private
	 * @param void
	 * @return void
	 */
	private function reset()
	{
		CacheManager::clearInstances();
	}

	/**
	 * @access public
	 * @param string $key
	 * @return mixed
	 */
	public function get($key)
	{
		$key = Stringify::formatKey($key);
		$this->cache = $this->adapter->getItem($key);
		return $this->cache->get();
	}

	/**
	 * @access public
	 * @param mixed $data
	 * @param mixed $tags
	 * @return bool
	 */
	public function set($data, $tags = null)
	{
		$this->cache->set($data)
		->expiresAfter(self::$ttl);
		if ( $tags ) {
			if ( TypeCheck::isArray($tags) ) {
				foreach ($tags as $key => $value) {
					$tags[$key] = Stringify::formatKey($value);
				}
				$this->cache->addTags($tags);
			} else {
				$tags = Stringify::formatKey($tags);
				$this->cache->addTag($tags);
			}
		}
		return $this->adapter->save($this->cache);
	}

	/**
	 * @access public
	 * @param string $key
	 * @param mixed $data
	 * @return bool
	 */
	public function update($key, $data)
	{
		$key = Stringify::formatKey($key);
		$this->cache = $this->adapter->getItem($key);
		$this->cache->set($data)
		->expiresAfter(self::$ttl);
		return $this->adapter->save($this->cache);
	}

	/**
	 * @access public
	 * @param string $key
	 * @return bool
	 */
	public function delete($key)
	{
		$key = Stringify::formatKey($key);
		return $this->adapter->deleteItem($key);
	}

	/**
	 * @access public
	 * @param mixed $tags
	 * @return bool
	 */
	public function deleteByTag($tags = '')
	{
		if ( TypeCheck::isArray($tags) ) {
			foreach ($tags as $key => $value) {
				$tags[$key] = Stringify::formatKey($value);
			}
			return $this->adapter->deleteItemsByTags($tags);
		} else {
			$tags = Stringify::formatKey($tags);
			return $this->adapter->deleteItemsByTag($tags);
		}
	}

	/**
	 * @access public
	 * @param void
	 * @return bool
	 */
	public function isCached()
	{
		if ( $this->cache ) {
			return $this->cache->isHit();
		}
		return false;
	}

	/**
	 * @access public
	 * @param void
	 * @return void
	 */
	public function purge()
	{
		// Secured removing : filecache
		if ( Stringify::contains($this->getTempPath(), $this->getRoot()) ) {
			File::clearDir($this->getTempPath());
		}

		// Secured removing : template cache on debug
		if ( $this->isDebug() ) {
			if ( Stringify::contains($this->getCachePath(), $this->getRoot()) ) {
				File::clearDir($this->getCachePath());
			}
		}
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
}
