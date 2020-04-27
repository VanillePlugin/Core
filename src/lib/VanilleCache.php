<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.0.1
 * @copyright : (c) 2018 - 2020 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\lib;

use phpFastCache\CacheManager;
use VanillePlugin\int\VanilleCacheInterface;
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

	private const EXPIRE = 30;
	private const EXTENSION = 'db';

	/**
	 * @param void
	 * @return void
	 */
	public function __construct()
	{
		// Init plugin config
		$this->initConfig();

		// Set cache path
		if ( !self::$path ) {
			self::$path = $this->getCachePath();
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
		$this->cache = $this->adapter->getItem( $this->formatKey($key) );
		return $this->isCached = $this->cache->get();
	}

	/**
	 * @access public
	 * @param mixed $data
	 * @return void
	 */
	public function set($data)
	{
		$this->cache->set($data)
		->expiresAfter( self::$expireIn );
		$this->adapter->save( $this->cache );
	}

	/**
	 * @access public
	 * @param string $key
	 * @param mixed $data
	 * @return void
	 */
	public function update($key, $data)
	{
		$this->cache = $this->adapter->getItem( $this->formatKey($key) );
		$this->cache->set($data)
		->expiresAfter( self::$expireIn );
		$this->adapter->save( $this->cache );
	}

	/**
	 * @access public
	 * @param string $key
	 * @return void
	 */
	public function delete($key)
	{
		$this->adapter->deleteItem( $this->formatKey($key) );
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
		self::$path = wp_normalize_path($path);
	}

	/**
	 * @access private
	 * @param string $key
	 * @return string
	 */
	private function formatKey($key)
	{
	    $chars = [
	        "{"  => "rcb",
	        "}"  => "lcb",
	        "("  => "rpn",
	        ")"  => "lpn",
	        "/"  => "fsl",
	        "\\" => "bsl",
	        "@"  => "ats",
	        ":"  => "cln"
	    ];
	    foreach ($chars as $character => $replacement) {
	        if (strpos($key, $character)) {
	            $key = str_replace($character, "~".$replacement."~", $key);
	        }
	    }
	    return $key;
	}

	/**
	 * @access public
	 * @param void
	 * @return void
	 */
	public static function remove()
	{
		new self;

		if ( is_dir(self::$path) ) $handler = opendir(self::$path);
		if ( !$handler ) return false;

	   	while( $file = readdir($handler) ) {
			if ($file !== '.' && $file !== '..') {
			    if ( !is_dir(self::$path.'/'.$file) ) {
			    	@unlink(self::$path.'/'.$file);
			    } else {
			    	$dir = self::$path.'/'.$file;
				    foreach( scandir($dir) as $file ) {
				        if ( '.' === $file || '..' === $file ) continue;
				        if ( is_dir("{$dir}/{$file}") ) self::recursiveRemove("{$dir}/{$file}");
				        else unlink("{$dir}/{$file}");
				    }
				    @rmdir($dir);
			    }
			}
	   }
	   closedir($handler);
	   return true;
	}

	/**
	 * @access private
	 * @param string $dir
	 * @return void
	 */
	private static function recursiveRemove($dir)
	{
		if ( is_dir($dir) ) {
			$objects = scandir($dir);
			foreach ($objects as $object) {
				if ($object !== '.' && $object !== '..') {
					if (filetype($dir.'/'.$object) == 'dir') self::recursiveRemove($dir.'/'.$object);
					else unlink($dir.'/'.$object);
				}
			 }
			reset($objects);
			rmdir($dir);
		}
	}

	/**
	 * @access public
	 * @param void
	 * @return void
	 */
	public static function removeAll()
	{
		$plugin = parent::getStatic();
		self::setPath( dirname($plugin->getCachePath()) );
		self::remove();
		ThirdPartyCache::purge();
	}
}
