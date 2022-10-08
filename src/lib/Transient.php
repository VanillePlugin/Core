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

use VanillePlugin\int\PluginNameSpaceInterface;
use VanillePlugin\inc\TypeCheck;

/**
 * Wrapper Class for Advanced Transients API.
 */
final class Transient extends PluginOptions
{
	/**
	 * @access private
	 * @var int $ttl, cache TTL
	 */
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
	}

	/**
	 * Retrieves the value of a transient.
	 *
	 * @access public
	 * @param int|string $key
	 * @return mixed
	 */
	public function get($key)
	{
		if ( $this->isMultisite() && $this->allowedMultisite() ) {
			return $this->getSiteTransient($key);
		}
		return $this->getTransient($key);
	}

	/**
	 * Set/update the value of a transient.
	 * 
	 * @access public
	 * @param int|string $key
	 * @param mixed $value
	 * @param int $expire
	 * @return bool
	 */
	public function set($key, $value, $expire = null)
	{
		if ( TypeCheck::isNull($expire) ) {
			$expire = self::$ttl;
		}
		if ( $this->isMultisite() && $this->allowedMultisite() ) {
			return $this->setSiteTransient($key,$value,$expire);
		}
		return $this->setTransient($key,$value,$expire);
	}

	/**
	 * Deletes a transient.
	 * 
	 * @access public
	 * @param int|string $key
	 * @param string $group
	 * @return bool
	 */
	public function delete($key)
	{
		if ( $this->isMultisite() && $this->allowedMultisite() ) {
			return $this->deleteSiteTransient($key);
		}
		return $this->deleteTransient($key);
	}

	/**
	 * Deletes all transients (Under namespace).
	 * 
	 * @access public
	 * @param void
	 * @return void
	 */
	public function flush()
	{
		if ( $this->isMultisite() && $this->allowedMultisite() ) {
			return $this->deleteSiteTransients();
		}
		return $this->deleteTransients();
	}

	/**
	 * Set global transient expiration.
	 * 
	 * @access public
	 * @param int $ttl 30
	 * @return void
	 */
	public static function expireIn($ttl = 30)
	{
		self::$ttl = $ttl;
	}
}
