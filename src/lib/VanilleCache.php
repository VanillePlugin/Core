<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.2.6
 * @copyright : (c) 2018 - 2020 JIHAD SINNAOUR <mail@jihadsinnaour.com>
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
 * Wrapper Class for External Filecache
 * Includes Third-Party Cache Helper
 */
class VanilleCache extends PluginOptions implements VanilleCacheInterface
{
	/**
	 * @access private
	 * @var object $cache, cache object
	 * @var object $adapter, adapter object
	 * @var boolean $isCached, cache status
	 * @var string $path, cache path
	 * @var int $ttl, cache TTL
	 */
	private $cache = false;
	private $adapter = false;
	private $isCached = false;
	private $path = false;
	private static $ttl = false;

	/**
	 * @param PluginNameSpaceInterface $plugin
	 * @return void
	 */
	public function __construct(PluginNameSpaceInterface $plugin)
	{
		// Init plugin config
		$this->initConfig($plugin);

		// Set default ttl
		if (!self::$ttl) {
			self::expireIn($this->getExpireIn());
		}

		// Set default path
		$this->path = "{$this->getCachePath()}/data";
		
		// Set adapter default params
		CacheManager::setDefaultConfig([
		    'path' => $this->path,
		    'default_chmod' => 777,
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
		$key = Stringify::formatKey($key);
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
			$tag = Stringify::formatKey($tag);
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
		$key = Stringify::formatKey($key);
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
		$key = Stringify::formatKey($key);
		$this->adapter->deleteItem($key);
	}

	/**
	 * @access public
	 * @param string $tag
	 * @return void
	 */
	public function deleteByTag($tag)
	{
		$this->adapter->deleteItemsByTag($tag);
	}

	/**
	 * @access public
	 * @param void
	 * @return boolean
	 */
	public function isCached()
	{
		return ($this->isCached) ? true : false;
	}

	/**
	 * @access public
	 * @param string $path null
	 * @return void
	 */
	public function clear($path = null)
	{
		// Secured clear
		$this->path = ($path) ? $path : $this->path;
		if ( Stringify::contains($this->path, $this->getRoot()) ) {
			$dir = new File();
			$dir->emptyDir($this->path);
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
		ThirdPartyCache::purge();
	}
}
