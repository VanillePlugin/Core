<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.4.3
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
	 * @var boolean $isCached, cache status
	 * @var int $ttl, cache TTL
	 */
	private $cache = false;
	private $adapter = false;
	private $isCached = false;
	private static $ttl = false;

	/**
	 * @param PluginNameSpaceInterface $plugin
	 */
	public function __construct(PluginNameSpaceInterface $plugin)
	{
		// Init plugin config
		$this->initConfig($plugin);

		// Set default ttl
		if (!self::$ttl) {
			self::expireIn($this->getExpireIn());
		}
		
		// Set adapter default params
		CacheManager::setDefaultConfig([
		    'path' => $this->getTempPath(),
		    'default_chmod' => 0755,
		    'cacheFileExtension' => 'db'
		]);

		// Set adapter instance
		$this->adapter = CacheManager::getInstance('Files');
	}

	/**
	 * @access public
	 * @param void
	 * @return void
	 */
	public function __destruct()
	{
		// Clear adapter instances
		CacheManager::clearInstances();
	}

	/**
	 * @access public
	 * @param string $key
	 * @return mixed
	 */
	public function get($key)
	{
		$key = Stringify::slugify($key);
		$this->cache = $this->adapter->getItem($key);
		return $this->isCached = $this->cache->get();
	}

	/**
	 * @access public
	 * @param mixed $data
	 * @param string $tag null
	 * @return void
	 */
	public function set($data, $tag = null)
	{
		$this->cache->set($data)
		->expiresAfter(self::$ttl);
		if ($tag) {
			$tag = Stringify::slugify($tag);
			$this->cache->addTag($tag);
		}
		$this->adapter->save($this->cache);
	}

	/**
	 * @access public
	 * @param string $key
	 * @param mixed $data
	 * @return void
	 */
	public function update($key, $data)
	{
		$key = Stringify::slugify($key);
		$this->cache = $this->adapter->getItem($key);
		$this->cache->set($data)
		->expiresAfter(self::$ttl);
		$this->adapter->save($this->cache);
	}

	/**
	 * @access public
	 * @param string $key
	 * @return void
	 */
	public function delete($key)
	{
		$key = Stringify::slugify($key);
		$this->adapter->deleteItem($key);
	}

	/**
	 * @access public
	 * @param string $tag
	 * @return void
	 */
	public function deleteByTag($tag)
	{
		$tag = Stringify::slugify($tag);
		$this->adapter->deleteItemsByTag($tag);
	}

	/**
	 * @access public
	 * @param void
	 * @return bool
	 */
	public function isCached()
	{
		return ($this->isCached) ? true : false;
	}

	/**
	 * @access public
	 * @param void
	 * @return void
	 */
	public function clear()
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
