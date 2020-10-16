<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.2.0
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

class VanilleCache extends PluginOptions implements VanilleCacheInterface
{
	/**
	 * @access private
	 * @var object $adapter, adapter instance
	 * @var object $cache, adapter object
	 * @var boolean $isCached, cache bool
	 * @var int $expireIn, cache TTL
	 * @var string $path, cache path
	 */
	private $adapter;
	private $cache;
	private $isCached = false;
	private static $path = null;
	private static $expireIn = null;

	const EXPIRE = 30;
	const EXTENSION = 'db';

	/**
	 * @param PluginNameSpaceInterface $plugin
	 * @return void
	 */
	public function __construct(PluginNameSpaceInterface $plugin)
	{
		// Init plugin config
		$this->initConfig($plugin);

		// Set cache path
		if ( !self::$path ) {
			// Default data cache folder
			self::$path = "{$this->getCachePath()}/data";
		}

		// Set cache expire
		if ( !self::$expireIn ) {
			self::expireIn();
		}
		
		// Set adapter default params
		CacheManager::setDefaultConfig([
		    'path' => self::$path,
		    'cacheFileExtension' => self::EXTENSION
		]);

		global $cacheAdapter;
		if ( !$cacheAdapter ) {
			$cacheAdapter = CacheManager::getInstance('Files');
		}
		$this->adapter = $cacheAdapter;
	}

	/**
	 * @access public
	 * @param string $key
	 * @return mixed
	 */
	public function get($key)
	{
		$this->cache = $this->adapter->getItem(Stringify::formatKey($key));
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
		->expiresAfter(self::$expireIn);
		if ($tag) {
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
		$this->cache = $this->adapter->getItem(Stringify::formatKey($key));
		$this->cache->set($data)
		->expiresAfter(self::$expireIn);
		$this->adapter->save($this->cache);
	}

	/**
	 * @access public
	 * @param string $key
	 * @return void
	 */
	public function delete($key)
	{
		$this->adapter->deleteItem(Stringify::formatKey($key));
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
		if ( !$this->isCached ) return false;
		else return true;
	}

	/**
	 * @access public
	 * @param int $expire
	 * @return void
	 */
	public static function expireIn($expire = self::EXPIRE)
	{
		self::$expireIn = $expire;
	}

	/**
	 * @access public
	 * @param string $path
	 * @return void
	 */
	public static function setPath($path)
	{
		self::$path = Stringify::formatPath($path);
	}

	/**
	 * @access public
	 * @param void
	 * @return void
	 */
	public function remove()
	{
		$dir = new File();
		$dir->emptyDir(self::$path);
	}

	/**
	 * @access public
	 * @param void
	 * @return void
	 */
	public function removeAll()
	{
		// Reset Cache Folder
		$this->setPath($this->getCachePath());
		$this->remove();
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
