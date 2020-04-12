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
 * Allowed to edit for plugin customization
 */

namespace winamaz\core\system\includes;

use phpFastCache\CacheManager;
use winamaz\core\system\includes\thirdparty\Wprocket;
use winamaz\core\system\includes\thirdparty\Litespeed;

class Cache
{
	/**
	 * @access private
	 */
	private $adapter; // instance adapter object
	private $cache; // cache object
	private $key; // cache ID
	private static $path;
	private static $instance;

	/**
	 * @access public
	 */
	public static $expire;

	const DEFAULT_PATH = '/core/storage/cache/app/';
	const DEFAULT_EXPIRE = 5; // int|DateTime

	/**
	 * @param void
	 * @return void
	 */
	public function __construct()
	{
		$config = new Config;
		// set default params
		if (!static::$path) {
			static::$path = $config->root . self::DEFAULT_PATH;
		}
		if (!static::$expire) {
			static::$expire = self::DEFAULT_EXPIRE;
		}

		CacheManager::setDefaultConfig([
		    'path' => static::$path,
		    'cacheFileExtension' => 'db'
		]);

		global $cacheAdapter;
		if ( !$cacheAdapter ) {
			$cacheAdapter = CacheManager::getInstance('files');
		}

		$this->adapter = $cacheAdapter;
	}

	/**
	 * @param string $key
	 * @return void
	 */
	public function get($key)
	{
		$this->cache = $this->adapter->getItem( $this->formatID($key) );
		return $this->cache->get();
	}

	/**
	 * @param mixed $data
	 * @return void
	 */
	public function set($data)
	{
		$this->cache->set($data)->expiresAfter(static::$expire);
		$this->adapter->save($this->cache);
	}

	/**
	 * @param mixed $data
	 * @return void
	 */
	public function delete($key)
	{
		$this->adapter->getItem( $this->formatID($key) );
	}

	/**
	 * @param void
	 * @return boolean
	 */
	public function isCached()
	{
		if ( is_null($this->cache->get()) ) return false;
		else return true;
	}

	/**
	 * @param void
	 * @return void
	 */
	public static function setConfig($config = [])
	{
		extract($config);
		static::$expire = isset($expire) ? $expire : '';
		static::$path = isset($path) ? $path : '';
	}

	/**
	 * @param string $key
	 * @return string
	 */
	private function formatID($key)
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
	 * @param string $string
	 * @return string
	 */
	public static function remove()
	{
		Wprocket::purge();
		Litespeed::purge();
		new self;

		if( is_dir(static::$path) ) $handler = opendir(static::$path);
		if( !$handler ) return false;

	   	while( $file = readdir($handler) ) {
			if ($file !== '.' && $file !== '..') {
			    if ( !is_dir(static::$path.'/'.$file) ) @unlink(static::$path.'/'.$file);
			    else {
			    	$dir = static::$path.'/'.$file;
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
	 * @param string $string
	 * @return string
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
	 * @param string $string
	 * @return string
	 */
	public static function removeAll()
	{
		$config = new Config;
		self::setConfig([
			'path' => "{$config->root}/core/storage/cache/"
		]);
		self::remove();
	}
}
