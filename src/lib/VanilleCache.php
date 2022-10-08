<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.8.0
 * @copyright : (c) 2018 - 2022 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

namespace VanillePlugin\lib;

use Phpfastcache\CacheManager;
use Phpfastcache\Drivers\Files\Config;
use VanillePlugin\int\VanilleCacheInterface;
use VanillePlugin\int\PluginNameSpaceInterface;
use VanillePlugin\inc\File;
use VanillePlugin\inc\Stringify;
use VanillePlugin\inc\TypeCheck;
use VanillePlugin\inc\Exception;
use VanillePlugin\thirdparty\Cache as ThirdPartyCache;

/**
 * Wrapper Class for External Filecache & Templates Cache,
 * Includes Third-Party Cache Helper.
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
		// $exception = new Exception();
		// $exception->trigger('VanilleCache is deprecated, Use Cache instead',E_USER_DEPRECATED);

		// Init plugin config
		$this->initConfig($plugin);

		// Set default ttl
		if ( !self::$ttl ) {
			self::expireIn($this->getExpireIn());
		}

		// Set adapter default config
		CacheManager::setDefaultConfig(new Config([
			'path'               => $this->getTempPath(),
			'autoTmpFallback'    => true,
			'compressData'       => true,
			'defaultChmod'       => 0755,
			'securityKey'        => 'private',
			'cacheFileExtension' => 'db'
		]));

		// Init adapter
		$this->reset();
		$this->adapter = CacheManager::getInstance('Files');
	}

	/**
	 * Clear adapter instances.
	 */
	public function __destruct()
	{
		$this->reset();
	}

	/**
	 * Reset cache instance.
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
	 * Get cache by key.
	 *
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
	 * Set cache by tags.
	 *
	 * @access public
	 * @param mixed $value
	 * @param mixed $tags
	 * @return bool
	 */
	public function set($value, $tags = null)
	{
		$this->cache->set($value)
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
	 * Update cache by key.
	 *
	 * @access public
	 * @param string $key
	 * @param mixed $value
	 * @return bool
	 */
	public function update($key, $value)
	{
		$key = Stringify::formatKey($key);
		$this->cache = $this->adapter->getItem($key);
		$this->cache->set($value)
		->expiresAfter(self::$ttl);
		return $this->adapter->save($this->cache);
	}

	/**
	 * Delete cache by key.
	 *
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
	 * Delete cache by tags.
	 *
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
	 * Check cache.
	 *
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
	 * flush cache.
	 *
	 * @access public
	 * @param void
	 * @return void
	 */
	public function flush()
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
	 * Set global cache expiration.
	 * 
	 * @access public
	 * @param int $ttl 30
	 * @return void
	 */
	public static function expireIn($ttl = 30)
	{
		self::$ttl = intval($ttl);
	}

	/**
	 * Remove WordPress 3rd-party cache.
	 * 
	 * @access public
	 * @param void
	 * @return void
	 */
	public static function removeThirdParty()
	{
		ThirdPartyCache::purge();
	}
}
